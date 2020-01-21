<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use DateTime;

class DatepickerValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
//        dd(__METHOD__, $value, $constraint);
//        if (!$constraint instanceof PostStatus) {
//            throw new UnexpectedTypeException($constraint, PostStatus::class);
//        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');
        }

        $date = DateTime::createFromFormat('d.m.Y', $value);

        if (!$date instanceof DateTime) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ date }}', $value)
                ->addViolation();
        }
    }
}
