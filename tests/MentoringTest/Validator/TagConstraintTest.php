<?php

namespace MentoringTest\Validator;

use Mentoring\Validator\Constraints\TagConstraintValidator;
use Mentoring\Validator\Constraints\TagConstraint;
use Mentoring\Taxonomy\Term;

class TagConstraintTest extends \PHPUnit_Framework_TestCase
{

    public function testInvalidArguments()
    {
        $validator  = new TagConstraintValidator();
        $constraint = new TagConstraint();

        $this->setExpectedException('InvalidArgumentException');

        $validator->validate(null, $constraint);
    }

    public function testInvalidTag()
    {
        $validator  = new TagConstraintValidator();
        $constraint = new TagConstraint();
        $context = $this->getMockBuilder('Symfony\Component\Validator\ExecutionContext')-> disableOriginalConstructor()->getMock();

        $context->expects($this->once())
            ->method('addViolation')
            ->with($this->equalTo('You must specify at least one topic.'));

        $validator->initialize($context);

        $term       = new Term();
        $invalidTag = [$term];
        $validator->validate($invalidTag, $constraint);
    }

    public function testValidTags()
    {
        $validator  = new TagConstraintValidator();
        $constraint = new TagConstraint();
        $context = $this->getMockBuilder('Symfony\Component\Validator\ExecutionContext')-> disableOriginalConstructor()->getMock();

        $context->expects($this->never())
            ->method('addViolation');

        $validator->initialize($context);

        $validTerm       = $this->getTestData();
        $validator->validate([$validTerm], $constraint);
    }

    protected function getTestData()
    {
        $term       = new Term();
        $term->setDescription('tag1 tag2 tag3');
        return $term;
    }
}