<?php

namespace Mentoring\PublicSite\Blog;

class BlogEntryHydrator
{
    /**
     * Extracts and returns the blog entry data from the blog entry object
     *
     * @param  BlogEntry $object
     * @return array
     */
    public function extract(BlogEntry $object)
    {
        return $object->toArray();
    }

    /**
     * Hydrates a blog entry object with the data
     *
     * @param array $data
     * @param BlogEntry $object
     *
     * @return BlogEntry
     */
    public function hydrate(array $data, BlogEntry $object)
    {
        $object->fromArray($data);

        return $object;
    }
}
