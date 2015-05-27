<?php

namespace Mentoring\Taxonomy;

class Vocabulary
{
    protected $id;
    protected $description;
    protected $enabled;
    protected $name;

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
}
