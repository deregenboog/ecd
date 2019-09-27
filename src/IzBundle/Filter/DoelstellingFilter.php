<?php

namespace IzBundle\Filter;

use AppBundle\Entity\Werkgebied;
use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Project;

class DoelstellingFilter implements FilterInterface
{
    /**
     * @var int
     */
    public $jaar;

    /**
     * @var Project
     */
    public $project;

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
        if ($this->jaar) {
            $builder
                ->andWhere('doelstelling.jaar = :jaar')
                ->setParameter('jaar', $this->jaar)
            ;
        }

        if ($this->project) {
            $builder
                ->andWhere('doelstelling.project = :project')
                ->setParameter('project', $this->project)
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
