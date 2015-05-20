<?php

namespace Mentoring\Conversation;

use Mentoring\User\User;

class Message
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
     * @var string
     */
    private $body;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var bool
     */
    private $read;

    public function __construct(User $fromUser, $body, \DateTime $created_at)
    {
        $this->fromUser = $fromUser;
        $this->body = $body;
        $this->created_at = $created_at;
        $this->read = false;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        if (null !== $this->id) {
            throw new \BadMethodCallException('id is already set on this message');
        }

        $this->id = (int) $id;
    }

    public function isFromUser(User $user)
    {
        return ($user === $this->fromUser) || ($user->getId() && $user->getId() == $this->fromUser->getId());
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    public function isRead()
    {
        return $this->read;
    }

    public function markRead()
    {
        $this->read = true;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
}
