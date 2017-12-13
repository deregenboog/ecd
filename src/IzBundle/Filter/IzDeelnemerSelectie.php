<?php

namespace IzBundle\Filter;

use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\IzProject;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Filter\FilterInterface;

class IzDeelnemerSelectie implements FilterInterface
{
    /**
     * @var ArrayCollection|IzProject[]
     */
    public $izProjecten;

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

    /**
     * @var string
     */
    public $formaat;

    public function applyTo(QueryBuilder $builder)
    {
        if ('izKlant' === current($builder->getRootAliases())) {
            if ($this->izProjecten) {
                $builder
                    ->andWhere('izProject.id IN (:iz_projecten)')
                    ->setParameter('iz_projecten', $this->izProjecten)
                ;
            }

            if ($this->stadsdelen) {
                if (in_array('-', $this->stadsdelen)) {
                    $builder->andWhere("klant.werkgebied IS NULL OR klant.werkgebied = '' OR klant.werkgebied IN (:stadsdelen)");
                } else {
                    $builder->andWhere('klant.werkgebied IN (:stadsdelen)');
                }
                $builder->setParameter('stadsdelen', $this->stadsdelen);
            }

            if (in_array('geen_post', $this->communicatie)) {
                $builder->andWhere('klant.geenPost = false OR klant.geenPost IS NULL');
            }

            if (in_array('geen_email', $this->communicatie)) {
                $builder->andWhere('klant.geenEmail = false OR klant.geenEmail IS NULL');
            }
        }

        if ('izVrijwilliger' === current($builder->getRootAliases())) {
            if ($this->izProjecten) {
                $builder
                    ->andWhere('izProject.id IN (:iz_projecten)')
                    ->setParameter('iz_projecten', $this->izProjecten)
                ;
            }

            if ($this->stadsdelen) {
                if (in_array('-', $this->stadsdelen)) {
                    $builder->andWhere("vrijwilliger.werkgebied IS NULL OR vrijwilliger.werkgebied = '' OR vrijwilliger.werkgebied IN (:stadsdelen)");
                } else {
                    $builder->andWhere('vrijwilliger.werkgebied IN (:stadsdelen)');
                }
                $builder->setParameter('stadsdelen', $this->stadsdelen);
            }

            if (in_array('geen_post', $this->communicatie)) {
                $builder->andWhere('vrijwilliger.geenPost = false OR vrijwilliger.geenPost IS NULL');
            }

            if (in_array('geen_email', $this->communicatie)) {
                $builder->andWhere('vrijwilliger.geenEmail = false OR vrijwilliger.geenEmail IS NULL');
            }
        }
    }
}
