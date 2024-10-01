<?php

namespace VillaBundle\Service;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityManagerInterface;
use DoctrineExtensions\Query\Mysql\Over;
use VillaBundle\Entity\Overnachting;
use VillaBundle\Service\OvernachtingDaoInterface;

class OvernachtingDao extends AbstractDao implements OvernachtingDaoInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Overnachting::class);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function findOvernachtingenByEntityIdForDateRange(int $entityId, $start, $end)
    {
        $query = $this->repository
            ->createQueryBuilder('overnachting')
            ->where('overnachting.datum BETWEEN :start AND :end')
            ->andWhere('overnachting.slaper = :slaper')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->setParameter('slaper',$entityId)
            ->getQuery();

        return $query->getResult();
    }
    public function create(Overnachting $overnachting)
    {
        $this->entityManager->persist($overnachting);
        $this->entityManager->flush();
    }

    public function update(Overnachting $overnachting)
    {
        $this->entityManager->flush();
    }

    public function delete(Overnachting $overnachting)
    {
        $this->entityManager->remove($overnachting);
        $this->entityManager->flush();
    }
}
