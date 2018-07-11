<?php

namespace GaBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

class SelectieFilter implements FilterInterface
{
    /**
     * @var ArrayCollection|Groep[]
     */
    public $groepen;

    /**
     * @var array
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
        if (in_array('klant', $builder->getAllAliases())) {
            $entity = 'klant';
        } elseif (in_array('vrijwilliger', $builder->getAllAliases())) {
            $entity = 'vrijwilliger';
        }

        $classLidmaatschap = sprintf('GaBundle\Entity\%sLidmaatschap', ucfirst($entity));
        $builder
//             ->addSelect('MIN(lidmaatschap.startdatum) AS startdatum')
            ->innerJoin($classLidmaatschap, 'lidmaatschap', 'WITH', "lidmaatschap.{$entity} = {$entity}")
            ->innerJoin('lidmaatschap.groep', 'groep')
            ->andWhere('lidmaatschap.startdatum <= :today')
            ->andWhere('lidmaatschap.einddatum IS NULL OR lidmaatschap.einddatum > :today')
            ->groupBy("{$entity}.id")
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
                ->andWhere("{$entity}.werkgebied IN (:stadsdelen)")
                ->setParameter('stadsdelen', $this->stadsdelen)
            ;
        }

        if (in_array('post', $this->communicatie)) {
            $builder->andWhere("{$entity}.geenPost = false OR {$entity}.geenPost IS NULL");
        }

        if (in_array('email', $this->communicatie)) {
            $builder->andWhere("{$entity}.geenEmail = false OR {$entity}.geenEmail IS NULL");
        }
    }
}
