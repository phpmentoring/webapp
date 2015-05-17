<?php

namespace Mentoring\Taxonomy;

class Term
{
    protected $id;
    protected $description;
    protected $enabled;
    protected $name;

    /**
     * @var Vocabulary
     */
    protected $vocabulary;

    protected $vocabulary_id;

    public function getDescription()
    {
        return $this->description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isEnabled()
    {
        return (bool)$this->enabled;
    }

    public function getVocabulary()
    {
        return $this->vocabulary;
    }

    /**
     * Returns the static Vocabulary ID set for this term.
     * If you want the actual Vocabulary object, use getVocabulary instead.
     *
     * @return int
     */
    public function getVocabularyId()
    {
        return $this->vocabulary_id;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setIsEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setVocabulary(Vocabulary $vocabulary)
    {
        $this->vocabulary_id = $vocabulary->getId();
        $this->vocabulary = $vocabulary;
    }

    public function setVocabularyId($vocabulary_id)
    {
        $this->vocabulary_id = $vocabulary_id;
    }
}