<?php

namespace HsBundle\DataFixtures;

use Doctrine\ORM\EntityManager;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;
use HsBundle\Service\FactuurFactoryInterface;

final class HsFakerProvider
{
    private $entityManager;

    private $factuurFactory;

    public function __construct(EntityManager $entityManager, FactuurFactoryInterface $factuurFactory)
    {
        $this->entityManager = $entityManager;
        $this->factuurFactory = $factuurFactory;
    }

    /**
     * @param Klant
     *
     * @return Factuur
     */
    public function factuur(Klant $klant)
    {
        $factuur = $this->factuurFactory->create($klant);

        $this->entityManager->persist($klant);
        $this->entityManager->persist($factuur);

        return $factuur;
    }
}
