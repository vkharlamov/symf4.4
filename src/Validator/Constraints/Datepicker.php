<?php


namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Datepicker extends Constraint
{
    public $message = 'The date "{{ date }}" bad format. Use dd.mm.yyyy';
}