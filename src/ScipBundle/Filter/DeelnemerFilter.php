<?php

namespace ScipBundle\Filter;

use AppBundle\Filter\FilterInterface;
use AppBundle\Filter\KlantFilter;
use Doctrine\ORM\QueryBuilder;
use ScipBundle\Entity\Project;
use ScipBundle\Entity\Document;
use ScipBundle\Entity\Deelnemer;

class DeelnemerFilter implements FilterInterface
{
    /**
     * @var KlantFilter
     */
    public $klant;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $type;

    /**
     * @var Project
     */
    public $project;

    /**
     * @var bool
     */
    public $actief = true;

    /**
     * @var int
     */
    public $vog;

    /**
     * @var int
     */
    public $overeenkomst;

    public function applyTo(QueryBuilder $builder)
    {
        if ($this->label) {
            $builder->andWhere('label = :label')->setParameter('label', $this->label);
        }

        if ($this->type) {
            $builder->andWhere('deelnemer.type = :type')->setParameter('type', $this->type);
        }

        if ($this->project) {
            $builder->andWhere('project = :project')->setParameter('project', $this->project);
        }

        if ($this->actief) {
            $builder->andWhere('project.id IS NOT NULL');
        }

        if (1 === $this->vog) {
            $builder
                ->innerJoin('deelnemer.documenten', 'vog', 'WITH', 'vog.type = :vog')
                ->setParameter('vog', Document::TYPE_VOG)
            ;
        } elseif (0 === $this->vog) {
            $deelnemers = $builder->getEntityManager()->createQueryBuilder()
                ->select('deelnemer.id')
                ->from(Deelnemer::class, 'deelnemer')
                ->innerJoin('deelnemer.documenten', 'vog', 'WITH', 'vog.type = :vog')
                ->setParameter('vog', Document::TYPE_VOG)
                ->getQuery()
                ->getResult()
            ;
            $builder
                ->andWhere('deelnemer.id NOT IN (:ids)')
                ->setParameter('ids', array_map(function($row) {
                    return $row['id'];
                }, $deelnemers))
            ;
        }

        if (1 === $this->overeenkomst) {
            $builder
                ->innerJoin('deelnemer.documenten', 'overeenkomst', 'WITH', 'overeenkomst.type = :overeenkomst')
                ->setParameter('overeenkomst', Document::TYPE_OVEREENKOMST)
            ;
        } elseif (0 === $this->overeenkomst) {
            $deelnemers = $builder->getEntityManager()->createQueryBuilder()
                ->select('deelnemer.id')
                ->from(Deelnemer::class, 'deelnemer')
                ->innerJoin('deelnemer.documenten', 'overeenkomst', 'WITH', 'overeenkomst.type = :overeenkomst')
                ->setParameter('overeenkomst', Document::TYPE_OVEREENKOMST)
                ->getQuery()
                ->getResult()
            ;
            $builder
                ->andWhere('deelnemer.id NOT IN (:ids)')
                ->setParameter('ids', array_map(function($row) {
                    return $row['id'];
                }, $deelnemers))
            ;
        }

        if ($this->klant) {
            $this->klant->applyTo($builder);
        }
    }
}
