<?php

namespace Mentoring\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Mentoring\Taxonomy\Term;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

class TagConstraintValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException('Array with terms expected but not found in TagConstraintValidator');
        }
        foreach ($value as $term) {
            if (!$term instanceof Term) {
                throw new InvalidArgumentException('Term expected but not found in TagConstraintValidator');
            }
            if (strlen($term->getName()) <= 0) {
                $this->context->addViolation(
                    $constraint->message
                );
            }
        }
    }
}
