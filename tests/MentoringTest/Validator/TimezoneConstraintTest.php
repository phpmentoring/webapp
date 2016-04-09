<?php

namespace MentoringTest\Validator;

use Mentoring\Validator\Constraints\TimezoneConstraintValidator;
use Mentoring\Validator\Constraints\TimezoneConstraint;
use Mentoring\Taxonomy\Term;

class TimezoneConstraintTest extends \PHPUnit_Framework_TestCase
{

    public function testInvalidTag()
    {
        $validator = new TimezoneConstraintValidator();
        $constraint = new TimezoneConstraint();
        $context = $this->getMockBuilder('Symfony\Component\Validator\ExecutionContext')->disableOriginalConstructor()->getMock();

        $context->expects($this->once())
            ->method('addViolation')
            ->with($this->equalTo('Please select a valid timezone.'));

        $validator->initialize($context);

        $timezone = "Not a valid timezone";
        $invalidTimezone = $timezone;
        $validator->validate($invalidTimezone, $constraint);
    }

    public function testValidTags()
    {
        $validator = new TimezoneConstraintValidator();
        $constraint = new TimezoneConstraint();
        $context = $this->getMockBuilder('Symfony\Component\Validator\ExecutionContext')->disableOriginalConstructor()->getMock();

        $context->expects($this->never())
            ->method('addViolation');

        $validator->initialize($context);

        $validTimezone = "Europe/Vienna";
        $validator->validate($validTimezone, $constraint);
    }
}