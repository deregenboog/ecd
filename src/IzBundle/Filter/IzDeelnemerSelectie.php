<?php

namespace IzBundle\Filter;

use AppBundle\Filter\FilterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use IzBundle\Entity\Project;

class IzDeelnemerSelectie implements FilterInterface
{
    /**
     * @var ArrayCollection|Project[]
     */
    public $projecten;

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
        switch (current($builder->getRootAliases())) {
            case 'izKlant':
                $entity = 'klant';
                break;
            case 'izVrijwilliger':
                $entity = 'vrijwilliger';
                break;
        }

        if ($this->projecten && count($this->projecten)) {
            $builder
                ->andWhere('project.id IN (:projecten)')
                ->setParameter('projecten', $this->projecten)
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
