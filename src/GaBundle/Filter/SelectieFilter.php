<?php

namespace GaBundle\Filter;

use AppBundle\Entity\Werkgebied;
use AppBundle\Filter\FilterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use GaBundle\Entity\Groep;
use GaBundle\Entity\Klantdossier;
use GaBundle\Entity\Vrijwilligerdossier;

class SelectieFilter implements FilterInterface
{
    /**
     * @var ArrayCollection|Groep[]
     */
    public $groepen;

    /**
     * @var ArrayCollection|Werkgebied[]
     */
    public $stadsdelen;

    /**
     * @var array
     */
    public $personen;

    /**
     * @var array
     */
    public $communicatie;

    public function applyTo(QueryBuilder $builder)
    {
        switch ($builder->getDQLPart('from')[0]->getFrom()) {
            case Klantdossier::class:
                $builder->innerJoin('dossier.klant', 'base');
                break;
            case Vrijwilligerdossier::class:
                $builder->innerJoin('dossier.vrijwilliger', 'base');
                break;
            default:
                throw new \LogicException('This should not be reached!');
        }

        $builder
            ->andWhere('lidmaatschap.id IS NOT NULL')
            ->andWhere('DATE(lidmaatschap.startdatum) <= :today')
            ->andWhere('lidmaatschap.einddatum IS NULL OR DATE(lidmaatschap.einddatum) > :today')
            ->groupBy('dossier.id')
            ->setParameter('today', new \DateTime('today'))
        ;

        if ($this->groepen && count($this->groepen)) {
            $builder
                ->andWhere('groep.id IN (:groepen)')
                ->setParameter('groepen', $this->groepen)
            ;
        }

        if ($this->stadsdelen && count($this->stadsdelen)) {
            $builder
                ->andWhere('base.werkgebied IN (:stadsdelen)')
                ->setParameter('stadsdelen', $this->stadsdelen)
            ;
        }

        if (in_array('post', $this->communicatie)) {
            $builder->andWhere('base.geenPost = false OR base.geenPost IS NULL');
        }

        if (in_array('email', $this->communicatie)) {
            $builder
                ->andWhere('base.email IS NOT NULL')
                ->andWhere('base.geenEmail = false OR base.geenEmail IS NULL');
        }
    }
}
