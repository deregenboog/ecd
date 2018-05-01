<?php

namespace HsBundle\DataFixtures;

use Nelmio\Alice\ProcessorInterface;
use HsBundle\Entity\Klus;
use HsBundle\Entity\Registratie;

final class HsProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function preProcess($object)
    {
        if ($object instanceof Klus) {
            $object->getKlant()->addKlus($object);
        }

        if ($object instanceof Registratie) {
            $object->setArbeider($object->getKlus()->getDienstverleners()[0]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postProcess($object)
    {
        // do nothing
    }
}
