<?php

namespace HsBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use HsBundle\Entity\Activiteit;

class KlusFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var \DateTime
     */
    public $datum = null;

    /**
     * @var Activiteit
     */
    public $activiteit;

    /**
     * @var KlantFilter
     */
    public $klant;

    public function applyTo(QueryBuilder $builder)
    {
//         if ($this->nummer) {
//             $builder
//                 ->andWhere('factuur.nummer LIKE :nummer')
//                 ->setParameter('nummer', "%{$this->nummer}%")
//             ;
//         }

//         if ($this->datum instanceof \DateTime) {
//             $builder
//                 ->andWhere('factuur.datum = :datum')
//                 ->setParameter('datum', $this->datum)
//             ;
//         }

//         if ($this->bedrag) {
//             $builder
//                 ->andWhere('factuur.bedrag = :bedrag')
//                 ->setParameter('bedrag', $this->bedrag)
//             ;
//         }

//         if ($this->openstaand) {
//             $builder
//                 ->leftJoin('factuur.betalingen', 'betaling')
//                 ->having('(SUM(factuur.bedrag) - SUM(betaling.bedrag)) > 0')
//                 ->orHaving('SUM(factuur.bedrag) > 0 AND COUNT(betaling) = 0')
//                 ->groupBy('factuur')
//             ;
//         }

//         if ($this->klant) {
//             $this->klant->applyTo($builder);
//         }
    }
}
