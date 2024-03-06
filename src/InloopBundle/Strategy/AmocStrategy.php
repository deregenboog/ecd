<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

final class AmocStrategy implements StrategyInterface
{

    private $accessStrategyName = "amoc_stadhouderskade";

    /** @var Locatie */
    private $locatie;

    private $amoc_locaties = [];

    private $amocVerblijfsstatus = "";

    /**
     * Deze strategie houdt in dat alleen voor de AMOC locaties mensen geselecteerd worden die ofwel geen specifieke
     * AMOC toegang tot hebben, of dat deze nog in de toekomst ligt.
     *
     * Er wordt niet gekeken naar verblijfsstatus.
     *
     * @param string[] $amoc_locaties
     */
    public function __construct(array $accessStrategies, $amocVerblijfsstatus)
    {
        $this->amoc_locaties = $accessStrategies[$this->accessStrategyName];
        $this->amocVerblijfsstatus = $amocVerblijfsstatus;
    }

    /**
     * @param Locatie $locatie
     * @return bool
     */
    public function supports(Locatie $locatie)
    {
        $this->locatie = $locatie;

        return in_array($locatie->getNaam(), $this->amoc_locaties);
    }

    /**
     * {@inheritdoc}
     *
     * @see \InloopBundle\Strategy\StrategyInterface::buildQuery()
     * @see https://github.com/deregenboog/ecd/issues/249
     */
    public function buildQuery(QueryBuilder $builder)
    {
        $builder->orWhere("( eersteIntake.toegangInloophuis = true AND (eersteIntakeLocatie.naam = 'AMOC Stadhouderskade' OR (eersteIntakeLocatie.naam = 'AMOC West' AND eersteIntake.intakedatum < :sixmonthsago) ) )");
//        $builder->setParameter('locatie',$this->locatie->getNaam());
        $builder->setParameter('sixmonthsago',new \DateTime("-4 months") );
    }
}
