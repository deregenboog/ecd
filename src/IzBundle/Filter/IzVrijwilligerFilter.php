<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\IzProject;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\VrijwilligerFilter;
use AppBundle\Filter\FilterInterface;

class IzVrijwilligerFilter implements FilterInterface
{
    /**
     * @var VrijwilligerFilter
     */
    public $vrijwilliger;

    /**
     * @var IzProject
     */
    public $izProject;

    /**
     * @var Medewerker
     */
    public $medewerker;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->vrijwilliger) {
            $this->vrijwilliger->applyTo($builder);
        }

        if ($this->izProject) {
            $builder
                ->andWhere('izHulpaanbod.izProject = :izProject')
                ->setParameter('izProject', $this->izProject)
            ;
        }

        if ($this->medewerker) {
            $builder
                ->andWhere('izHulpaanbod.medewerker = :medewerker')
                ->setParameter('medewerker', $this->medewerker)
            ;
        }
    }
}
