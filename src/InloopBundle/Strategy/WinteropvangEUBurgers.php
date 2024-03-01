<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\LocatieType;
use InloopBundle\Strategy\StrategyInterface;

final class WinteropvangEUBurgers implements StrategyInterface
{
    private $accessStrategyName = "winteropvang_euburgers";

    /** @var Locatie */
    private $locatie;

    private $amoc_locaties = [];

    private $amocVerblijfsstatus = "";

    /** @var EntityManagerInterface  */
    private $entityManager;

    /**
     * @var LocatieType|object|null
     */
    private $locatieType;

    /**
     * deze strategie geldt alleen voor locatie die als type 'Nachtopvang' hebben.
     */
    public function __construct(array $accessStrategies, $amocVerblijfsstatus, EntityManagerInterface $entityManager)
    {
//        $this->amoc_locaties = $accessStrategies[$this->accessStrategyName];
//        $this->amocVerblijfsstatus = $amocVerblijfsstatus;
        $this->entityManager = $entityManager;
        $this->locatieType = $this->entityManager->getRepository(LocatieType::class)->findOneBy(["naam"=>"Nachtopvang"]);
    }

    /**
     * @param Locatie $locatie
     * @return bool
     */
    public function supports(Locatie $locatie)
    {
        return $locatie->hasLocatieType($this->locatieType);
    }

    /**
     * {@inheritdoc}
     *
     * @see \InloopBundle\Strategy\StrategyInterface::buildQuery()
     * @see https://github.com/deregenboog/ecd/issues/249
     */
    public function buildQuery(QueryBuilder $builder)
    {
        $builder->orWhere('huidigeStatus IS NOT NULL');
    }
}
