<?php

namespace HsBundle\DataFixtures;

use HsBundle\Entity\Klus;
use HsBundle\Entity\Registratie;
use Fidry\AliceDataFixtures\ProcessorInterface;

final class HsProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function preProcess($fixtureId, $object):void
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
    public function postProcess($fixtureId,$object):void
    {
        // do nothing
    }
}
