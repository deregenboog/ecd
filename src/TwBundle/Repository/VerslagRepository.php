<?php

namespace TwBundle\Repository;

use Doctrine\ORM\EntityRepository;
use TwBundle\Entity\Klant;

class VerslagRepository extends EntityRepository
{
    public function findAll(): array
    {
        $builder = $this->createQueryBuilder('verslag');
        $builder->where('verslag INSTANCE OF Verslag');

        return $builder->getQuery()->getResult();
    }

    public function getMwVerslagenForKlant(Klant $klant)
    {
        return $this->createQueryBuilder('v')
            ->join('v.klant', 'k')
            ->where('v.delenMw = :delenMw')
            ->andWhere('v INSTANCE OF TwBundle\Entity\Verslag')
            ->andWhere('k.id = :klantId')
            ->setParameter('delenMw', true)
            ->setParameter('klantId', $klant->getId())
            ->getQuery()
            ->getResult();
    }
}
