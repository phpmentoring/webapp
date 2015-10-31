<?php
/**
 * Created by IntelliJ IDEA.
 * User: billie
 * Date: 05/11/2015
 * Time: 08:24
 */

namespace Mentoring\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TimezoneConstraint extends Constraint
{
    public $message = 'Please select a valid timezone.';
}
