<?php

namespace Mentoring\User;

use Mentoring\Taxonomy\Term;

class User
{
    protected $id;
    protected $email;
    protected $roles = [];
    protected $name = '';
    protected $timeCreated;
    protected $isEnabled = true;
    protected $githubUid = null;
    protected $githubName = '';
    protected $isMentor = false;
    protected $isMentee = false;
    protected $timezone = null;
    protected $profile = '';
    protected $mentorTags = [];
    protected $apprenticeTags = [];
    protected $profileImage = null;
    protected $sendNotifications = true;

    public function addApprenticeTag(Term $term)
    {
        $this->apprenticeTags[$term->getId()] = $term;
    }

    public function addMentorTag(Term $term)
    {
        $this->mentorTags[$term->getId()] = $term;
    }

    public function getApprenticeTags()
    {
        return $this->apprenticeTags;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getGithubUid()
    {
        return $this->githubUid;
    }

    public function getGithubName()
    {
        return $this->githubName;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getMentorTags()
    {
        return $this->mentorTags;
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

    public function setApprenticeTags(array $terms)
    {
        $this->apprenticeTags = $terms;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setGithubUid($uid)
    {
        $this->githubUid = $uid;
    }

    public function setGithubName($name)
    {
        $this->githubName = $name;
    }

    public function setId($id)
    {
        $this->id = (int)$id;
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

    public function setMentorTags(array $terms)
    {
        $this->mentorTags = $terms;
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

    public function getProfile()
    {
        return $this->profile;
    }

    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    public function getProfileImage()
    {
        return 'https://avatars0.githubusercontent.com/u/' . $this->githubUid;
    }

    public function setSendNotifications($sendNotifications)
    {
        $this->sendNotifications = (bool) $sendNotifications;
    }

    public function hasSendNotifications()
    {
        return $this->sendNotifications;
    }

    public function getTimezone()
    {
        return $this->timezone;
    }

    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }
}
