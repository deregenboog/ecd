<?php

namespace MwBundle\DataFixtures;

use Doctrine\ORM\EntityManager;
use Fidry\AliceDataFixtures\ProcessorInterface;
use MwBundle\Entity\DossierStatus;

final class MwProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function preProcess(string $fixtureId, $object): void
    {
        if ($object instanceof DossierStatus) {
            $object->getKlant()->addDossierStatus($object);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function postProcess(string $fixtureId, $object): void
    {
        // do nothing
    }
}