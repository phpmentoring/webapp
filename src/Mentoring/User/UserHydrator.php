<?php

namespace Mentoring\User;

use Mentoring\Taxonomy\TaxonomyService;
use Mentoring\Taxonomy\TermHydrator;

class UserHydrator
{
    protected $taxonomyService;
    protected $termHydrator;

    public function __construct(TaxonomyService $taxonomyService, TermHydrator $termHydrator)
    {
        $this->taxonomyService = $taxonomyService;
        $this->termHydrator = $termHydrator;
    }

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
            'profile' => $object->getProfile(),
            'apprenticeTags' => $object->getApprenticeTags(),
            'mentorTags' => $object->getMentorTags(),
            'imageUrl'   => $object->getProfileImage(),
        ];

        if (!is_null($this->termHydrator)) {
            $apprenticeTags = $data['apprenticeTags'];
            unset($data['apprenticeTags']);
            foreach($apprenticeTags as $key => $tag) {
                $tag = $this->termHydrator->extract($tag);
                $apprenticeTags[$key] = $tag;
            }
            $data['apprenticeTags'] = $apprenticeTags;

            $mentorTags = $data['mentorTags'];
            unset($data['mentorTags']);
            foreach($mentorTags as $key => $tag) {
                $tag = $this->termHydrator->extract($tag);
                $mentorTags[$key] = $tag;
            }
            $data['mentorTags'] = $mentorTags;
        }

        if ($data['timeCreated'] instanceof \DateTime) {
            $data['timeCreated'] = $data['timeCreated']->format(\DateTime::ISO8601);
        }

        return $data;
    }

    /**
     * Hydrates a user object with the data
     *
     * @param array $data
     * @param User $object
     *
     * @return User
     */
    public function hydrate(array $data, User $object)
    {
        $object->setEmail($data['email']);
        $object->setName($data['name']);


        $object->setIsMentee($data['isMentee']);
        $object->setIsMentor($data['isMentor']);

        if (isset($data['isEnabled'])) {
            $object->setIsEnabled($data['isEnabled']);
        }

        if (isset($data['githubUid'])) {
            $object->setGithubUid($data['githubUid']);
        }

        if (isset($data['timeCreated'])) {
            if (!$data['timeCreated'] instanceof \DateTime) {
                $createdTime = new \DateTime($data['timeCreated']);
                $object->setTimeCreated($createdTime);
            } else {
                $object->setTimeCreated($data['timeCreated']);
            }
        }

        if (isset($data['id'])) {
            $object->setId($data['id']);
        }

        if (isset($data['roles'])) {
            if (is_array($data['roles'])) {
                $object->setRoles($data['roles']);
            } else {
                $object->setRoles(unserialize($data['roles']));
            }
        }

        if (isset($data['profile'])) {
            $object->setProfile($data['profile']);
        }

        $mentoringTerm = $this->taxonomyService->fetchVocabularyByName('mentor');
        $apprenticeTerm = $this->taxonomyService->fetchVocabularyByName('apprentice');

        $object->setMentorTags($this->taxonomyService->fetchTermsForUser($object, $mentoringTerm));
        $object->setApprenticeTags($this->taxonomyService->fetchTermsForUser($object, $apprenticeTerm));

        return $object;
    }
}
