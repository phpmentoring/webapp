<?php

namespace Mentoring\User;

class UserHydrator
{
    /**
     * Extracts and returns the user data from the user object
     *
     * @param  User $object
     * @return array
     */
    public function extract(User $object)
    {
        $data = [
            'email' => $object->getEmail(),
            'id' => $object->getId(),
            'name' => $object->getName(),
            'roles' => $object->getRoles(),
            'timeCreated' => $object->getTimeCreated(),
            'isEnabled' => $object->isEnabled(),
            'githubUid' => $object->getGithubUid(),
            'isMentee' => $object->isMentee(),
            'isMentor' => $object->isMentor(),
        ];

        if ($data['timeCreated'] instanceof \DateTime) {
            $data['timeCreated'] = $data['timeCreated']->format(\DateTime::ISO8601);
        }

        return $data;
    }

    /**
     * Hydrates a user object with the data
     *
     * @param array $data
     * @param User  $object
     *
     * @return User
     */
    public function hydrate(array $data, User $object)
    {
        $object->setEmail($data['email']);
        $object->setName($data['name']);
        $object->setIsEnabled($data['isEnabled']);
        $object->setGithubUid($data['githubUid']);
        $object->setIsMentee($data['isMentee']);
        $object->setIsMentor($data['isMentor']);

        if (!$data['timeCreated'] instanceof \DateTime) {
            $createdTime = new \DateTime($data['timeCreated']);
            $object->setTimeCreated($createdTime);
        } else {
            $object->setTimeCreated($data['timeCreated']);
        }

        if (isset($data['id'])) {
            $object->setId($data['id']);
        }

        if (is_array($data['roles'])) {
            $object->setRoles($data['roles']);
        } else {
            $object->setRoles(unserialize($data['roles']));
        }

        return $object;
    }
}
