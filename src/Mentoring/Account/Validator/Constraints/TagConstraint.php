<?php
namespace Mentoring\Account\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TagConstraint extends Constraint
{
    public $message = 'You must specify at least one topic.';
}
