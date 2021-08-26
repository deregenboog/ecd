<?php

namespace AppBundle\Filter;

use AppBundle\Entity\Werkgebied;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\AppDateType;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Project;

class DownloadVrijwilligersFilter implements FilterInterface
{

    /**
     * @var string
     */
    public $onderdeel;



    public function __construct()
    {
    }

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->onderdeel) {

        }


    }
}
