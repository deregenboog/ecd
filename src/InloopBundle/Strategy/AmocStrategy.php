<?php

namespace InloopBundle\Strategy;

use AppBundle\Doctrine\SqlExtractor;
use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;

class AmocStrategy implements StrategyInterface
{

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
    public function __construct(array $amoc_locaties, $amocVerblijfsstatus)
    {
        $this->amoc_locaties = $amoc_locaties;
        $this->amocVerblijfsstatus = $amocVerblijfsstatus;
    }

    /**
     * @param Locatie $locatie
     * @return bool
     */
    public function supports(Locatie $locatie)
    {
        $this->locatie = $locatie;

        $supported = in_array($locatie->getNaam(), $this->amoc_locaties);
        return $supported;
//        return in_array($locatie->getId(), $this->locatieIds);
    }

    /**
     * {@inheritdoc}
     *
     * @see \InloopBundle\Strategy\StrategyInterface::buildQuery()
     * @see https://github.com/deregenboog/ecd/issues/249
     */
    public function buildQuery(QueryBuilder $builder)
    {
        $builder->orWhere("(eersteIntakeLocatie.naam = 'AMOC' OR (eersteIntakeLocatie.naam = 'AMOC West' AND eersteIntake.intakedatum < :sixmonthsago) )");
//        $builder->setParameter('locatie',$this->locatie->getNaam());
        $builder->setParameter('sixmonthsago',new \DateTime("-6 months") );


    }
}
