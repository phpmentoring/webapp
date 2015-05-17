<?php

namespace Mentoring\Taxonomy;

class TermHydrator
{
    /**
     * Extracts and returns the user data from the Term object
     *
     * @param  Term $object
     * @return array
     */
    public function extract(Term $object)
    {
        $data = [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'description' => $object->getDescription(),
            'enabled' => $object->isEnabled(),
            'vocabulary_id' => (!is_null($object->getVocabulary()) ? $object->getVocabulary()->getId() : null),
        ];

        return $data;
    }

    /**
     * Hydrates a Term object with the data
     *
     * @param array $data
     * @param Term $object
     *
     * @return Term
     */
    public function hydrate(array $data, Term $object)
    {
        $object->setName($data['name']);
        $object->setIsEnabled($data['enabled']);
        $object->setDescription($data['description']);

        if (isset($data['id'])) {
            $object->setId($data['id']);
        }

        if (isset($data['vocabulary']) && $data['vocabulary'] instanceof Vocabulary) {
           $object->setVocabulary($data['vocabulary']);
        }

        if (isset($data['vocabulary_id'])) {
            $object->setVocabularyId($data['vocabulary_id']);
        }

        return $object;
    }
}
