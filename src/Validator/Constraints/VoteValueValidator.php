<?php

namespace App\Validator\Constraints;

use App\Entity\Vote;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class VoteValueValidator
 * @package App\Validator\Constraints
 */
class VoteValueValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
            throw new UnexpectedValueException($value, 'string');
        }

        if (!array_key_exists($value, Vote::VOTE)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
