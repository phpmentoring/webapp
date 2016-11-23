<?php

namespace Mentoring\Account\Form\DataTransformer;

use Mentoring\Taxonomy\TermNotFoundException;
use Symfony\Component\Form\DataTransformerInterface;
use Mentoring\Taxonomy\TaxonomyService;
use Mentoring\Taxonomy\Term;

class TextToTagsTransformer implements DataTransformerInterface
{
    /**
     * @var TaxonomyService
     */
    protected $taxonomyService;

    /**
     * @var String
     */
    protected $vocabulary;

    public function __construct(TaxonomyService $service, $vocabulary)
    {
        $this->taxonomyService = $service;
        $this->vocabulary = $vocabulary;
    }

    /**
     * Turns a collection of tags into a comma separated list
     *
     * @param mixed $tags
     * @return string
     */
    public function transform($tags)
    {
        if (!is_null($tags) && !empty($tags)) {
            $names = [];
            foreach ($tags as $tag) {
                $names[] = $tag->getName();
            }

            $names = join(', ', $names);
            return $names;
        }
    }

    /**
     * Turns a comma deleted list into a collection of tags
     *
     * @param mixed $tagList
     * @return array
     */
    public function reverseTransform($tagList)
    {
        $tags = explode(',', $tagList);
        $terms = [];
        foreach ($tags as $tagName) {
            try {
                $tagName = trim($tagName);
                $term = $this->taxonomyService->fetchTerm($this->vocabulary, $tagName);
            } catch (TermNotFoundException $e) {
                $term = new Term();
                $term->setDescription($tagName);
                $term->setName($tagName);
                $term = $this->taxonomyService->saveTerm($term, $this->vocabulary);
            }
            $terms[] = $term;
        }

        return $terms;
    }
}
