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

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('markdown', array($this, 'formatInMarkdown'), ['is_safe' => ['html']])
        );
    }

    public function formatInMarkdown($string)
    {
        $md = new \Parsedown();
        $markdown = $md->text($string);

        // always purify HTML after the markdown conversion
        $purify = \HTMLPurifier::getInstance();
        return $purify->purify($markdown);
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
