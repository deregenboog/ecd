<?php

namespace IzBundle\Repository;

use AppBundle\Repository\DoelstellingRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use IzBundle\Entity\Project;

class ProjectRepository extends EntityRepository implements DoelstellingRepositoryInterface
{
    /**
     * Finds all projects that are active on the given date.
     *
     * @param \DateTime $date
     *
     * @return array
     */
    public function findActive(\DateTime $date = null)
    {
        $builder = $this->createQueryBuilder('p')
            ->where('p.startdatum <= :date')
            ->andWhere('p.einddatum IS NULL OR p.einddatum > :date')
            ->orderBy('p.naam', 'ASC')
            ->setParameter('date', $date ?: new \DateTime())
        ;

        return $builder->getQuery()->getResult();
    }

    public function getKpis(): array
    {

       $r = $this->findActive();
       $ret = [];
       foreach($r as $k=>$v)
        {
            /**
             * @var Project $v
             */
            $ret[$v->getNaam() ] = $v->getId();
        }
       return $ret;
    }

    public static function getPrestatieLabel():string
    {
        return "Informele Zorg";
    }
}
