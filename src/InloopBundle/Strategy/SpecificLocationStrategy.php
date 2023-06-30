<?php

namespace InloopBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use InloopBundle\Entity\Locatie;
use InloopBundle\Service\LocatieDaoInterface;

class SpecificLocationStrategy implements StrategyInterface
{

    protected LocatieDaoInterface  $locatieDao;

    protected Locatie $locatie;

    /**
     * @param LocatieDaoInterface $locatieDao
     */
    public function __construct(LocatieDaoInterface $locatieDao)
    {
        $this->locatieDao = $locatieDao;
    }


    public function supports(Locatie $locatie)
    {
        $this->locatie = $locatie;
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @see \InloopBundle\Strategy\StrategyInterface::buildQuery()
     * @see https://github.com/deregenboog/ecd/issues/249
     */
    public function buildQuery(QueryBuilder $builder)
    {
        /**
         * Selecteer alle klanten die specifieke locaties hebben genoemd in hun toegangsprofiel.
         */
        $builder
            ->leftJoin("eersteIntake.specifiekeLocaties","specifiekeLocaties")
            ->orWhere("(eersteIntake.toegangInloophuis = true AND specifiekeLocaties IN (:locaties) )")
            ->setParameter("locaties",$this->locatie)
        ;
    }
}
