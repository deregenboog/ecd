<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\IzProject;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\KlantFilter;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\Model\AppDateRangeModel;
use AppBundle\Entity\Werkgebied;

class DoelgroepFilter implements FilterInterface
{
    public function applyTo(QueryBuilder $builder)
    {
    }
}
