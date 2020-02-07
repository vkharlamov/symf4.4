<?php


namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class VoteValue extends Constraint
{
    public $message = 'The value "{{ value }}" an illegal.';
}