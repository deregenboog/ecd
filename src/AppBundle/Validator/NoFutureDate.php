<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NoFutureDate extends Constraint
{
    public $message = 'Deze datum mag niet in de toekomst liggen.';
}
