<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class FutureDate extends Constraint
{
    public $message = 'Deze datum moet in de toekomst liggen.';
}
