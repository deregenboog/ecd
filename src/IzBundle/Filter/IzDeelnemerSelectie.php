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
        switch (current($builder->getRootAliases())) {
            case 'izKlant':
                $entity = 'klant';
                break;
            case 'izVrijwilliger':
                $entity = 'vrijwilliger';
                break;
        }

        if ($this->izProjecten && count($this->izProjecten)) {
            $builder
                ->andWhere('izProject.id IN (:iz_projecten)')
                ->setParameter('iz_projecten', $this->izProjecten)
            ;
        }

        if ($this->stadsdelen && count($this->stadsdelen)) {
            $builder
                ->andWhere("{$entity}.werkgebied IN (:stadsdelen)")
                ->setParameter('stadsdelen', $this->stadsdelen)
            ;
        }

        if (in_array('geen_post', $this->communicatie)) {
            $builder->andWhere("{$entity}.geenPost = false OR {$entity}.geenPost IS NULL");
        }

        if (in_array('geen_email', $this->communicatie)) {
            $builder->andWhere("{$entity}.geenEmail = false OR {$entity}.geenEmail IS NULL");
        }
    }
}
