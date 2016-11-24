<?php

namespace Mentoring\PublicSite\Blog;

class BlogEntry
{
    /**
     * @var int
     */
    protected $id;
    protected $filename;
    protected $author;
    protected $email;
    protected $post_date;
    protected $published;
    protected $slug;
    protected $title;
    protected $body;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Converts an array into a Blog Entry
     *
     * @param array $data
     */
    public function fromArray(array $data)
    {
        $this->id = isset($data['id']) ? $this->setId($data['id']) : null;
        $this->filename = isset($data['filename']) ? $data['filename'] : '';
        $this->author = isset($data['author']) ? $data['author'] : '';
        $this->email = isset($data['email']) ? $data['email'] : '';
        $this->post_date = isset($data['post_date']) ? $data['post_date'] : '';
        $this->published = isset($data['published']) ? $data['published'] : '';
        $this->slug = isset($data['slug']) ? $data['slug'] : '';
        $this->title = isset($data['title']) ? $data['title'] : '';
        $this->body = isset($data['body']) ? $data['body'] : '';
    }

    /**
     * Sets the ID of the blog entry
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Turns the blog entry into an array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'filename' => $this->filename,
            'author' => $this->author,
            'email' => $this->email,
            'post_date' => $this->post_date,
            'published' => $this->published,
            'slug' => $this->slug,
            'title' => $this->title,
            'body' => $this->body,
        ];
    }
}