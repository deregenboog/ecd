<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class NoFutureDateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!$value instanceof \DateTime) {
            throw new UnexpectedTypeException($value, \DateTime::class);
        }

        if ($value > new \DateTime('now')) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
