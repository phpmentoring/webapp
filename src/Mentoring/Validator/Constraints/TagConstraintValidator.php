<?php
namespace Mentoring\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

class TagConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if(!$value || !isset($value[0])){
            throw new InvalidArgumentException('Object expected but not found in TagConstraintValidator');
        }

        $tags = $value[0];

        if ( strlen($tags->getDescription()) <= 0)
        {
            $this->context->addViolation(
                $constraint->message
            );

        }
    }
}