<?php

namespace Mentoring\User;

use Mentoring\Taxonomy\TaxonomyService;

class UserService
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $dbal;

    /**
     * @var UserHydrator
     */
    protected $hydrator;

    /**
     * @var User[]
     */
    protected $in_memory_users;

    public function __construct($dbal, $hydrator)
    {
        $this->dbal = $dbal;
        $this->hydrator = $hydrator;
        $this->in_memory_users = array();
    }

    /**
     * @param $id
     * @return User
     * @throws UserNotFoundException
     */
    public function fetchUserById($id)
    {
        if (!array_key_exists($id, $this->in_memory_users)) {
            $data = $this->dbal->fetchAssoc('SELECT * FROM users WHERE id = :user_id', ['user_id' => $id]);

            if (!$data) {
                throw new UserNotFoundException('Could not find user with ID ' . $id);
            }

            $user = $this->hydrator->hydrate($data, new User());

            $this->in_memory_users[$user->getId()] = $user;
        }

        return $this->in_memory_users[$id];
    }

    public function saveUser(User $user)
    {
        $data = $this->hydrator->extract($user);
        $data['roles'] = serialize($data['roles']);
        unset($data['mentorTags']);
        unset($data['apprenticeTags']);
        unset($data['imageUrl']);

        if (empty($data['id'])) {
            $this->dbal->insert('users', $data);
            $user->setId($this->dbal->lastInsertId());
        } else {
            $response = $this->dbal->update('users', $data, ['id' => $data['id']]);
        }

        $this->saveUserTags($user);

        return $user;
    }

    public function fetchMentees()
    {
        $data = $this->dbal->fetchAll('SELECT * FROM users WHERE isMentee = 1');
        $users = [];
        foreach ($data as $userData) {
            $users[] = $this->hydrator->hydrate($userData, $user = new User());
            $this->in_memory_users[$user->getId()] = $user;
        }

        return $users;
    }

    public function fetchMentors()
    {
        $data = $this->dbal->fetchAll('SELECT * FROM users WHERE isMentor = 1');
        $users = [];
        foreach ($data as $userData) {
            $users[] = $this->hydrator->hydrate($userData, $user = new User());
            $this->in_memory_users[$user->getId()] = $user;
        }

        return $users;
    }

    public function fetchUserByGithubUid($uid)
    {
        $user = $this->dbal->fetchAssoc('SELECT * FROM users WHERE githubUid = :githubUid', ['githubUid' => $uid]);
        if ($user) {
            $user = $this->hydrator->hydrate($user, new User());
            $this->in_memory_users[$user->getId()] = $user;

            return $user;
        }

        throw new UserNotFoundException('Could not find user with a UID of ' . $uid);
    }

    public function createUser($data)
    {
        $data['timeCreated'] = new \DateTime();
        $data['timezone'] = null;
        $data['isEnabled'] = true;
        $data['isMentee'] = false;
        $data['isMentor'] = false;
        $data['sendNotifications'] = true;

        $user = $this->hydrator->hydrate($data, new User());

        return $user;
    }

    public function deleteUser($user)
    {

    }

    public function saveUserTags(User $user)
    {
        $this->dbal->delete('userTags', ['user_id' => $user->getId()]);

        foreach ($user->getMentorTags() as $mentorTag) {
            echo 'Saving '.$mentorTag->getName();
            $this->dbal->insert('userTags', ['user_id' => $user->getId(), 'term_id' => $mentorTag->getId()]);
        }

        foreach ($user->getApprenticeTags() as $apprenticeTag) {
            echo 'Saving '.$apprenticeTag->getName();
            $this->dbal->insert('userTags', ['user_id' => $user->getId(), 'term_id' => $apprenticeTag->getId()]);
        }
    }
}
