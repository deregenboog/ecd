<?php

namespace OekraineBundle\Strategy;

use Doctrine\ORM\QueryBuilder;
use OekraineBundle\Entity\Locatie;

class OndroBongStrategy extends VerblijfsstatusStrategy
{
    // @todo do not define database ID here
    private $locatieIds = [13, 19];
    private $ondroBongoLocaties = ['Zeeburg', 'Transformatorweg', 'T6 Inloop'];

    public function supports(Locatie $locatie): bool
    {
        //        return in_array($locatie->getId(), $this->locatieIds);
        $supported = in_array($locatie->getNaam(), $this->ondroBongoLocaties);

        return $supported;
    }

    /**
     * @see https://github.com/deregenboog/ecd/issues/749
     */
    public function buildQuery(QueryBuilder $builder)
    {
        parent::buildQuery($builder);
        // LET OP: deze erft over van verblijfsstatus strategie.

        //        $builder->orWhere('laatsteIntake.ondroBongToegangVan <= DATE(CURRENT_TIMESTAMP())'); // was actief tot september 2020
        $builder->orWhere('eersteIntake.ondroBongToegangVan <= DATE(CURRENT_TIMESTAMP())');
    }
}
