<?php

namespace HsBundle\DataFixtures;

use HsBundle\Service\FactuurFactoryInterface;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;
use Doctrine\ORM\EntityManager;

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
