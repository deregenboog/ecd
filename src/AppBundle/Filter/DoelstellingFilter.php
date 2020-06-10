<?php

namespace AppBundle\Filter;

use AppBundle\Entity\Werkgebied;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\AppDateType;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Project;

class DoelstellingFilter implements FilterInterface
{

    /**
     * @var string
     */
    public $repository;

    /**
     * @var int
     */
    public $jaar;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $kostenplaats;

    /**
     * @var DateTime Startdatum
     */
    public $startdatum;

    /**
     * @var DateTime Einddatum
     */
    public $einddatum;

    public function __construct()
    {
        $this->jaar = date("Y");
        $this->startdatum = new \DateTime('first day of January this year');
        $this->einddatum = (AppDateType::getLastFullQuarterEnd());
    }

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->repository) {
            $builder
                ->andWhere('doelstelling.repository LIKE :repo')
                ->setParameter('repo', "%".$this->repository."%")
            ;
        }
        if ($this->jaar) {
            $builder
                ->andWhere('doelstelling.jaar = :jaar')
                ->setParameter('jaar', $this->jaar)
            ;
        }

        if ($this->label) {
            $builder
                ->andWhere('doelstelling.label LIKE :label')
                ->setParameter('label', $this->label)
            ;
        }

        if ($this->kostenplaats) {
            $builder
                ->andWhere('doelstelling.kostenplaats = :kostenplaats')
                ->setParameter('kostenplaats', $this->kostenplaats)
            ;
        }

    }
}
