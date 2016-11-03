<?php

namespace MentoringTest\Validator;

use Mentoring\Validator\Constraints\TagConstraintValidator;
use Mentoring\Validator\Constraints\TagConstraint;
use Mentoring\Taxonomy\Term;

class TagConstraintTest extends \PHPUnit_Framework_TestCase
{

    public function testNullIsInvalidArguments()
    {
        $validator  = new TagConstraintValidator();
        $constraint = new TagConstraint();

        $this->setExpectedException('InvalidArgumentException');

        $validator->validate(null, $constraint);
    }

    public function testNotTermIsInvalidArguments()
    {
        $validator  = new TagConstraintValidator();
        $constraint = new TagConstraint();

        $this->setExpectedException('InvalidArgumentException');

        $validator->validate(['test'], $constraint);
    }

    public function testInvalidTag()
    {
        $validator  = new TagConstraintValidator();
        $constraint = new TagConstraint();
        $context = \Mockery::mock('Symfony\Component\Validator\Context\ExecutionContextInterface');

        $context
            ->shouldReceive('addViolation')
            ->once()
            ->with('You must specify at least one topic.')
        ;

        $validator->initialize($context);

        $term       = new Term();
        $invalidTag = [$term];
        $validator->validate($invalidTag, $constraint);
    }

    public function testValidTags()
    {
        $validator  = new TagConstraintValidator();
        $constraint = new TagConstraint();
        $context = \Mockery::mock('Symfony\Component\Validator\Context\ExecutionContextInterface');

        $context
            ->shouldNotReceive('addViolation')
        ;

        $validator->initialize($context);

        $validTerm       = $this->getTestData();
        $validator->validate([$validTerm], $constraint);
    }

    protected function getTestData()
    {
        $term       = new Term();
        $term->setDescription('PHP');
        $term->setName('PHP');
        return $term;
    }
}