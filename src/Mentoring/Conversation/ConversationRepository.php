<?php

namespace Mentoring\Conversation;

use Doctrine\DBAL\Connection;
use Mentoring\User\User;
use Mentoring\User\UserService;

class ConversationRepository
{
    /**
     * @var Connection
     */
    private $dbal;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var Conversation[]
     */
    private $in_memory_convos;

    public function __construct(Connection $dbal, UserService $userService)
    {
        $this->dbal = $dbal;
        $this->userService = $userService;
        $this->in_memory_convos = [];
    }

    public function findAllInvolvingUser(User $user)
    {
        $convos_data = $this->dbal->fetchAll('SELECT * FROM conversations WHERE (from_user_id = :user_id OR to_user_id = :user_id)', ['user_id' => $user->getId()]);

        $conversations = [];
        foreach ($convos_data as $convo_data) {
            $conversation_id = $convo_data['id'];

            if (!array_key_exists($conversation_id, $this->in_memory_convos)) {
                $conversation = $this->hydrateConversation($convo_data);
                $this->in_memory_convos[$conversation->getId()] = $conversation;
            }

            $conversations[] = $this->in_memory_convos[$conversation_id];
        }

        return $conversations;
    }

    public function findById($id)
    {
        if (array_key_exists($id, $this->in_memory_convos)) {
            return $this->in_memory_convos[$id];
        }

        $convo_data = $this->dbal->fetchAssoc('SELECT * FROM conversations WHERE id = :conversation_id', ['conversation_id' => $id]);

        if (!$convo_data) {
            throw new ConversationNotFoundException(sprintf('Could not find conversation with ID of %s', $id));
        }

        return $this->hydrateConversation($convo_data);
    }

    protected function hydrateConversation(array $convo_data)
    {
        $messages_data = $this->dbal->fetchAll('SELECT * FROM messages WHERE conversation_id = :conversation_id', ['conversation_id' => $convo_data['id']]);

        $messages = [];
        foreach ($messages_data as $message_data) {
            $messages[] = $message = new Message(
                $this->userService->fetchUserById($message_data['from_user_id']),
                $message_data['body'],
                new \DateTime($message_data['created_at'])
            );

            $message->setId($message_data['id']);
        }

        $conversation = new Conversation(
            $this->userService->fetchUserById($convo_data['from_user_id']),
            $this->userService->fetchUserById($convo_data['to_user_id']),
            $convo_data['subject'],
            $messages[0]
        );
        $conversation->setId($convo_data['id']);

        foreach ($messages as $message) {
            $conversation->addMessage($message);
        }

        $this->in_memory_convos[$conversation->getId()] = $conversation;

        return $conversation;
    }

    public function save(Conversation $conversation)
    {
        $convo_data = [
            'id' => $conversation->getId(),
            'from_user_id' => $conversation->getFromUser()->getId(),
            'to_user_id' => $conversation->getToUser()->getId(),
            'subject' => $conversation->getSubject(),
            'started_at' => $conversation->getStartedAt()->format(\DateTime::ISO8601)
        ];

        if ($convo_data['id']) {
            $this->dbal->update('conversations', $convo_data, ['id' => $convo_data['id']]);
        } else {
            $this->dbal->insert('conversations', $convo_data);
            $conversation->setId($this->dbal->lastInsertId());
        }

        foreach ($conversation->getAllMessages() as $message) {
            $message_data = [
                'id' => $message->getId(),
                'conversation_id' => $conversation->getId(),
                'from_user_id' => $message->getFromUser()->getId(),
                'body' => $message->getBody(),
                'created_at' => $message->getCreatedAt()->format(\DateTime::ISO8601)
            ];

            if ($message_data['id']) {
                $this->dbal->update('messages', $message_data, ['id' => $message_data['id']]);
            } else {
                $this->dbal->insert('messages', $message_data);
                $message->setId($this->dbal->lastInsertId());
            }
        }
    }
}
