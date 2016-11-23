<?php

namespace MentoringTest\Account\Validator;

use Mentoring\Account\Validator\Constraints\TimezoneConstraintValidator;
use Mentoring\Account\Validator\Constraints\TimezoneConstraint;

class TimezoneConstraintTest extends \PHPUnit_Framework_TestCase
{

    public function testInvalidTag()
    {
        $validator = new TimezoneConstraintValidator();
        $constraint = new TimezoneConstraint();
        $context = \Mockery::mock('Symfony\Component\Validator\Context\ExecutionContextInterface');

        $context
            ->shouldReceive('addViolation')
            ->once()
            ->with('Please select a valid timezone.')
        ;

        $validator->initialize($context);

        $timezone = "Not a valid timezone";
        $invalidTimezone = $timezone;
        $validator->validate($invalidTimezone, $constraint);
    }

    public function testValidTags()
    {
        $validator = new TimezoneConstraintValidator();
        $constraint = new TimezoneConstraint();
        $context = \Mockery::mock('Symfony\Component\Validator\Context\ExecutionContextInterface');

        $context
            ->shouldNotReceive('addViolation')
        ;

        $validator->initialize($context);

        $validTimezone = "Europe/Vienna";
        $validator->validate($validTimezone, $constraint);
    }
}