<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

final class AmocStrategy implements StrategyInterface
{
    private const ACCESS_STRATEGY_NAME = 'amoc_stadhouderskade';

    /** @var Locatie */
    private $locatie;

    private $amoc_locaties = [];

    private $amocVerblijfsstatus = '';

    /**
     * Deze strategie houdt in dat alleen voor de AMOC locaties mensen geselecteerd worden die ofwel geen specifieke
     * AMOC toegang tot hebben, of dat deze nog in de toekomst ligt.
     *
     * Er wordt niet gekeken naar verblijfsstatus.
     */
    public function __construct(array $accessStrategies, $amocVerblijfsstatus)
    {
        $this->amoc_locaties = $accessStrategies[self::ACCESS_STRATEGY_NAME];
        $this->amocVerblijfsstatus = $amocVerblijfsstatus;
    }

    public function supports(Locatie $locatie): bool
    {
        return in_array($locatie->getNaam(), $this->amoc_locaties);
    }

    /**
     * @see \InloopBundle\Strategy\StrategyInterface::buildQuery()
     * @see https://github.com/deregenboog/ecd/issues/249
     */
    public function buildQuery(QueryBuilder $builder, Locatie $locatie)
    {
        $builder->orWhere("( eaf.toegangInloophuis = true AND (eersteIntakeLocatie.naam = 'AMOC Stadhouderskade' OR (eersteIntakeLocatie.naam = 'AMOC West' AND eaf.intakedatum < :four_months_ago) ) )");
        $builder->setParameter('four_months_ago', new \DateTime('-4 months'));
    }
}
