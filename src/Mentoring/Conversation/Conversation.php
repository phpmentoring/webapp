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

    /**
     * Shortcut to add a message to this conversation.
     *
     * @param User $fromUser
     * @param $body
     * @return Message
     */
    public function appendMessage(User $fromUser, $body)
    {
        $message = new Message($fromUser, $body, new \DateTime());
        $this->addMessage($message);

        return $message;
    }

    /**
     * Find the number of unread messages in this conversation for the given user.
     *
     * @param User $user
     * @return int
     */
    public function countUnread(User $user)
    {
        $unread = 0;

        foreach ($this->getAllMessages() as $message) {
            if (!$message->isFromUser($user) && !$message->isRead()) {
                $unread++;
            }
        }

        return $unread;
    }

    /**
     * Marks any message not sent by this user as read.
     *
     * @param User $user
     */
    public function markUserHasRead(User $user)
    {
        foreach ($this->messages as $message) {
            if (!$message->isFromUser($user)) {
                $message->markRead();
            }
        }
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
        if (!$this->hasMessage($message)) {
            $this->messages[] = $message;
        }
    }

    /**
     * Check to see if this conversation already holds a reference to the given message.
     *
     * @param Message $message
     * @return bool
     */
    public function hasMessage(Message $message)
    {
        foreach ($this->messages as $existing) {
            if ($existing === $message || ($existing->getId() && $existing->getId() == $message->getId())) {
                return true;
            }
        }

        return false;
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
     * The latest Message in this conversation.
     *
     * @return \DateTime
     */
    public function getLastMessage()
    {
        $latest_message = $this->messages[0];

        foreach ($this->messages as $message) {
            if ($message->getCreatedAt() > $latest_message->getCreatedAt()) {
                $latest_message = $message;
            }
        }

        return $latest_message;
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
     * Is the given user involved in this conversation?
     *
     * @param User $user
     * @return bool
     */
    public function involvesUser(User $user)
    {
        foreach ([$this->fromUser, $this->toUser] as $testUser) {
            if ($testUser === $user || ($testUser->getId() && $testUser->getId() == $user->getId())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Given an involved user, it will tell you the other involved user.
     *
     * @param User $user
     * @return User
     */
    public function withUser(User $user)
    {
        if ($user === $this->toUser || ($this->toUser->getId() && $this->toUser->getId() == $user->getId())) {
            return $this->fromUser;
        }

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

    /**
     * Get a list of all messages.
     *
     * @return Message[]
     */
    public function getAllMessages()
    {
        return $this->messages;
    }
}
