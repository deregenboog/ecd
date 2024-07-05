<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\LocatieType;

final class WinteropvangEUBurgersStrategy implements StrategyInterface
{
    /**
     * @var LocatieType
     */
    private $locatieType;

    /**
     * deze strategie geldt alleen voor locatie die als type 'Nachtopvang' hebben.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->locatieType = $entityManager->getRepository(LocatieType::class)->findOneBy(['naam' => 'Nachtopvang']);
    }

    public function supports(Locatie $locatie): bool
    {
        return $locatie->hasLocatieType($this->locatieType);
    }

    /**
     * @see \InloopBundle\Strategy\StrategyInterface::buildQuery()
     * @see https://github.com/deregenboog/ecd/issues/249
     */
    public function buildQuery(QueryBuilder $builder, Locatie $locatie)
    {
        $builder->orWhere('klant.huidigeStatus IS NOT NULL');
    }
}
