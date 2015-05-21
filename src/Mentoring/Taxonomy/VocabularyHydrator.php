<?php

namespace Mentoring\Taxonomy;

class VocabularyHydrator
{
    /**
     * Extracts and returns the user data from the vocabulary object
     *
     * @param  Vocabulary $object
     * @return array
     */
    public function extract(Vocabulary $object)
    {
        $data = [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'description' => $object->getDescription(),
            'enabled' => $object->isEnabled(),
        ];

        return $data;
    }

    /**
     * Hydrates a vocabulary object with the data
     *
     * @param array $data
     * @param Vocabulary $object
     *
     * @return Vocabulary
     */
    public function hydrate(array $data, Vocabulary $object)
    {
        $object->setName($data['name']);
        $object->setIsEnabled($data['enabled']);
        $object->setDescription($data['description']);

        if (isset($data['id'])) {
            $object->setId($data['id']);
        }

        return $object;
    }
}
