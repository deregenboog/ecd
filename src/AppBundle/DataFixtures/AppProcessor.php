<?php

namespace AppBundle\DataFixtures;

use Nelmio\Alice\ProcessorInterface;
use AppBundle\Entity\Zrm;

final class AppProcessor implements ProcessorInterface
{
    /**
     * @inheritdoc
     */
    public function preProcess($object)
    {
    }

    /**
     * @inheritdoc
     */
    public function postProcess($object)
    {
    }
}
