<?php

namespace Mentoring\PublicSite\Blog;

class BlogService
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function saveEntry(BlogEntry $entry)
    {

    }

    public function getEntry($id)
    {

    }
}