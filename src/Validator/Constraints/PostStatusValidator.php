<?php

namespace App\Validator\Constraints;

use App\Entity\Post;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use App\Validator\Constraints\PostStatus;

class PostStatusValidator extends ConstraintValidator
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

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }
        // is status correct
        if (!in_array($value, Post::STATUSES)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ status }}', $value)
                ->addViolation();
        }
    }
}
