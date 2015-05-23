<?php

namespace Mentoring\Conversation;

use Mentoring\User\User;

class ConversationTwigExtension extends \Twig_Extension
{
    /**
     * @var ConversationRepository
     */
    private $conversationRepository;

    public function __construct(ConversationRepository $conversationRepository)
    {
        $this->conversationRepository = $conversationRepository;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('unread_messages', array($this, 'getUnreadMessages'))
        );
    }

    public function getUnreadMessages(User $user)
    {
        $conversations = $this->conversationRepository->findAllInvolvingUser($user);

        $unread = 0;

        foreach ($conversations as $convo) {
            $unread += $convo->countUnread($user);
        }

        return $unread;
    }

    public function getName()
    {
        return 'conversation';
    }
}
