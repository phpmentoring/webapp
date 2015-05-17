<?php

namespace Mentoring\Taxonomy;

use Mentoring\User\User;

class TaxonomyService
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $dbal;

    /**
     * @var TermHydrator
     */
    protected $termHydrator;

    /**
     * @var VocabularyHydrator
     */
    protected $vocabularyHydrator;

    public function __construct($dbal, $vocabularyHydrator, $termHydrator)
    {
        $this->dbal = $dbal;
        $this->vocabularyHydrator = $vocabularyHydrator;
        $this->termHydrator = $termHydrator;
    }

    public function fetchAllTerms(Vocabulary $vocabulary)
    {
        $data = $this->dbal->fetchAll(
            'SELECT * FROM taxonomyTerms WHERE vocabulary_id = :id',
            ['id' => $vocabulary->getId()]
        );
        $terms = [];
        foreach($data as $termData) {
            $term = $this->termHydrator->hydrate($termData, new Term());
            $term->setVocabulary($vocabulary);
            $terms[] = $term;
        }

        return $terms;
    }

    public function fetchTerm($vocabulary, $term)
    {
        $termResult = $this->dbal->fetchAssoc(
            'SELECT t.* FROM taxonomyTerms t JOIN taxonomyVocabulary v ON t.vocabulary_id=v.id WHERE t.name = :tname AND v.name = :vname',
            [
                'tname' => $term,
                'vname' => $vocabulary
            ]
        );

        if ($termResult) {
            $termObject = $this->termHydrator->hydrate($termResult, new Term());
            return $termObject;
        }

        throw new TermNotFoundException('Could not find term ' . $term);
    }

    public function fetchVocabularyByName($name)
    {
        $vocabulary = $this->dbal->fetchAssoc('SELECT * FROM taxonomyVocabulary WHERE name = :name', ['name' => $name]);
        if ($vocabulary) {
            $vocabulary = $this->vocabularyHydrator->hydrate($vocabulary, new Vocabulary());
            return $vocabulary;
        }

        throw new VocabularyNotFoundException('Could not find vocabulary ' . $name);
    }

    public function saveTerm(Term $term, $vocabulary)
    {
        if (is_string($vocabulary)) {
            $vocabulary = $this->fetchVocabularyByName($vocabulary);
        }

        $term->setVocabulary($vocabulary);
        $data = $this->termHydrator->extract($term);

        if (empty($data['id'])) {
            $this->dbal->insert('taxonomyTerms', $data);
            $term->setId($this->dbal->lastInsertId());
        } else {
            $response = $this->dbal->update('taxonomyTerms', $data, ['id' => $data['id']]);
        }

        return $term;
    }

    public function fetchTermsForUser(User $user, Vocabulary $vocabulary)
    {
        $data = $this->dbal->fetchAll(
            'SELECT
                t.*
            FROM
                taxonomyTerms t
            JOIN
                userTags u ON t.id=u.term_id
            WHERE
                u.user_id = :user_id
                AND t.vocabulary_id = :vocabulary_id
            ',
            [
                'vocabulary_id' => $vocabulary->getId(),
                'user_id' => $user->getId(),
            ]
        );
        $terms = [];
        foreach($data as $termData) {
            $term = $this->termHydrator->hydrate($termData, new Term());
            $term->setVocabulary($vocabulary);
            $terms[] = $term;
        }

        return $terms;
    }
}