<?php

namespace Mentoring\Account\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

class TimezoneConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!in_array($value, \DateTimeZone::listIdentifiers())) {
            $this->context->addViolation(
                $constraint->message
            );
        }
    }
}
