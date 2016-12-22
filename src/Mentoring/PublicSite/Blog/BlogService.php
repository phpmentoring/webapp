<?php

namespace Mentoring\PublicSite\Blog;

class BlogService
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $dbal;

    /**
     * @var BlogEntryHydrator
     */
    protected $hydrator;

    /**
     * BlogService constructor.
     *
     * @param $dbal
     * @param BlogEntryHydrator $hydrator
     */
    public function __construct($dbal, BlogEntryHydrator $hydrator)
    {
        $this->dbal = $dbal;
        $this->hydrator = $hydrator;
    }

    /**
     * Returns the most recent entries
     *
     * @param int $limit
     * @return array
     */
    public function fetchRecentEntries($limit = 5)
    {
        $qb = $this->dbal->createQueryBuilder();
        $qb
            ->select('*')
            ->from('blog_entries')
            ->where('published = 1')
            ->orderBy('post_date')
            ->setMaxResults($limit)
        ;
        $data = $qb->execute();

        $entries = [];
        foreach ($data as $row) {
            $entries[] = $this->hydrator->hydrate($row, new BlogEntry());
        }

        return $entries;
    }

    /**
     * Returns a single post from the database based on the criteria supplied
     *
     * @param array $where
     *
     * @return BlogEntry
     */
    public function findEntry(array $where = [])
    {
        $qb = $this->dbal->createQueryBuilder();
        $qb
            ->select('*')
            ->from('blog_entries')
            ->setMaxResults(1)
        ;

        if (count($where)) {
            foreach ($where as $column => $value) {
                $qb->expr()->andX($qb->expr()->eq($column, $value));
            }
        }
        $data = $qb->execute();

        return $this->hydrator->hydrate($data->fetch(), new BlogEntry());
    }

    /**
     * Returns a blog entry based on a specific filename
     *
     * @param string $filename
     * @return BlogEntry
     * @throws BlogEntryNotFoundException
     */
    public function findEntryByFilename($filename)
    {
        $data = $this->dbal->fetchAssoc('SELECT * FROM blog_entries WHERE filename = :filename', ['filename' => $filename]);
        if (!$data) {
            throw new BlogEntryNotFoundException('Could not find blog with filename ' . $filename);
        }

        $entry = $this->hydrator->hydrate($data, new BlogEntry());

        return $entry;
    }

    /**
     * Removes all blog entries that are not in the supplied list of filenames
     *
     * @param $filenamesToKeep
     */
    public function massRemoveByFilename($filenamesToKeep)
    {
        $filenames = implode("', '", $filenamesToKeep);
        $this->dbal->executeQuery('DELETE FROM blog_entries WHERE filename NOT IN (:filenames)', ['filenames' => $filenames]);
    }

    /**
     * Saves a blog entry off to the database
     *
     * @param BlogEntry $entry
     * @return BlogEntry
     */
    public function saveEntry(BlogEntry $entry)
    {
        $data = $this->hydrator->extract($entry);

        if (empty($data['id'])) {
            try {
                $this->findEntryByFilename($data['filename']);
                $this->updateEntry($data);
            } catch (BlogEntryNotFoundException $e) {
                $this->dbal->insert('blog_entries', $data);
                $entry->setId($this->dbal->lastInsertId());
            }
        } else {
            $this->updateEntry($data);
        }

        return $entry;
    }

    /**
     * Updates an existing blog entry
     *
     * @param $data
     * @return int
     */
    protected function updateEntry($data)
    {
        return $this->dbal->update('blog_entries', $data, ['filename' => $data['filename']]);
    }
}