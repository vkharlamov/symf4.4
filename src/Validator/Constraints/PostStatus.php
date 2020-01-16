<?php


namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PostStatus extends Constraint
{
    public $message = 'The status "{{ status }}" an illegal.';
}