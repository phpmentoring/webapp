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

    public function __construct(User $fromUser, $body, \DateTime $created_at)
    {
        $this->fromUser = $fromUser;
        $this->body = $body;
        $this->created_at = $created_at;
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
