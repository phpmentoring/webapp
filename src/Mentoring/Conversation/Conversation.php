<?php

namespace Mentoring\Conversation;

use Mentoring\User\User;

class Conversation
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var User
     */
    private $fromUser;

    /**
     * @var User
     */
    private $toUser;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var \DateTime
     */
    private $startedAt;

    /**
     * @var Message[]
     */
    private $messages;

    public function __construct(User $fromUser, User $toUser, $subject, Message $opening_message)
    {
        $this->fromUser = $fromUser;
        $this->toUser = $toUser;
        $this->subject = $subject;
        $this->startedAt = $opening_message->getCreatedAt();
        $this->messages = array();
        $this->addMessage($opening_message);
    }

    /**
     * Convenience method to start a new conversation.
     *
     * @param User $fromUser
     * @param User $toUser
     * @param $subject
     * @param $opening_body
     * @param \DateTime $created_at
     * @return Conversation
     */
    public static function startNew(User $fromUser, User $toUser, $subject, $opening_body, \DateTime $created_at = null)
    {
        if (!$created_at) {
            $created_at = new \DateTime();
        }

        return new static($fromUser, $toUser, $subject, new Message($fromUser, $opening_body, $created_at));
    }

    public function setId($id)
    {
        if (null !== $this->id) {
            throw new \BadMethodCallException('id is already set on this conversation');
        }

        $this->id = (int) $id;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Add a new Message to this conversation
     *
     * @param Message $message
     */
    public function addMessage(Message $message)
    {
        $this->messages[] = $message;
    }

    /**
     * Get the opening message of this conversation.
     *
     * @return Message
     */
    public function getFirstMessage()
    {
        return $this->messages[0];
    }

    /**
     * Get an array of all replies (all messages added after the opening message)
     *
     * @return array
     */
    public function getReplies()
    {
        $replies = [];

        foreach ($this->messages as $index => $message) {
            if ($index > 0) {
                $replies[] = $message;
            }
        }

        return $replies;
    }

    /**
     * Get the message in this conversation that has the given ID.
     *
     * @param $id
     * @return Message
     */
    public function getMessageById($id)
    {
        foreach ($this->messages as $message) {
            if ($id == $message->getId()) {
                return $message;
            }
        }

    }

    /**
     * Get the user that initiated this conversation.
     *
     * @return User
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * Get the user that the conversation initiator sent the opening message to.
     *
     * @return User
     */
    public function getToUser()
    {
        return $this->toUser;
    }

    /**
     * Get the subject of the conversation.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Get the date that the first message was created (the conversation start date).
     *
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }
}
