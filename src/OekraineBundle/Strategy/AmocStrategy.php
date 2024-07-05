<?php

namespace OekraineBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use OekraineBundle\Entity\Locatie;

class AmocStrategy implements StrategyInterface
{
    // @todo do not define database ID here
    //    private $locatieIds = [5, 12, 22];
    //    private $locatieIds = [3, 7, 17];
    // @todo Instead of IDs now work with names. Is slower but more reliable when IDs of dev and prod differ. JTB 20190716

    private $amoc_locaties = ['AMOC', 'Nachtopvang De Regenboog Groep', 'Nachtopvang DRG', 'Amoc Gebruikersruimte'];

    public function supports(Locatie $locatie): bool
    {
        $supported = in_array($locatie->getNaam(), $this->amoc_locaties);

        return $supported;
        //        return in_array($locatie->getId(), $this->locatieIds);
    }

    /**
     * @see \OekraineBundle\Strategy\StrategyInterface::buildQuery()
     * @see https://github.com/deregenboog/ecd/issues/249
     */
    public function buildQuery(QueryBuilder $builder)
    {
        $builder->andWhere('eersteIntake.amocToegangTot IS NULL OR eersteIntake.amocToegangTot >= DATE(CURRENT_TIMESTAMP())');
    }
}
