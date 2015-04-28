<?php

namespace Mentoring\User;

class User
{
    protected $id;
    protected $email;
    protected $roles = array();
    protected $name = '';
    protected $timeCreated;
    protected $isEnabled = true;
    protected $githubUid = null;
    protected $isMentor = false;
    protected $isMentee = false;

    public function getEmail()
    {
        return $this->email;
    }

    public function getGithubUid()
    {
        return $this->githubUid;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getTimeCreated()
    {
        return $this->timeCreated;
    }

    public function isEnabled()
    {
        return $this->isEnabled;
    }

    public function isMentee()
    {
        return (bool)$this->isMentee;
    }

    public function isMentor()
    {
        return (bool)$this->isMentor;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setGithubUid($uid)
    {
        $this->githubUid = $uid;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setIsEnabled($status)
    {
        $this->isEnabled = $status;
    }

    public function setIsMentee($status)
    {
        $this->isMentee = $status;
    }

    public function setIsMentor($status)
    {
        $this->isMentor = $status;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function setTimeCreated(\DateTime $timeCreated)
    {
        $this->timeCreated = $timeCreated;
    }
}