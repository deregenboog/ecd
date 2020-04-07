<?php

namespace AppBundle\Filter;

use AppBundle\Entity\Werkgebied;
use AppBundle\Filter\FilterInterface;
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
    public $kpi;

    /**
     * @var string
     */
    public $categorie;

    /**
     * @var Werkgebied
     */
    public $stadsdeel;

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

        if ($this->kpi) {
            $builder
                ->andWhere('doelstelling.kpi = :kpi')
                ->setParameter('kpi', $this->kpi)
            ;
        }

        if ($this->categorie) {
            if ('-' === $this->categorie) {
                $builder->andWhere('doelstelling.categorie IS NULL');
            } else {
                $builder
                    ->andWhere('doelstelling.categorie = :categorie')
                    ->setParameter('categorie', $this->categorie)
                ;
            }
        }

        if ($this->stadsdeel) {
            $builder
                ->andWhere('doelstelling.stadsdeel = :stadsdeel')
                ->setParameter('stadsdeel', $this->stadsdeel)
            ;
        }
    }
}
