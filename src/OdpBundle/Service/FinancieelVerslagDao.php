<?php

namespace OdpBundle\Service;

use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityManagerInterface;
use OdpBundle\Entity\FinancieelVerslag;


class FinancieelVerslagDao extends AbstractDao implements FinancieelVerslagDaoInterface
{
    protected $class = FinancieelVerslag::class;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($this->class);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function create(FinancieelVerslag $verslag)
    {
        $this->entityManager->persist($verslag);
        $this->entityManager->flush();
    }

    public function update(FinancieelVerslag $verslag)
    {
        $this->entityManager->flush();
    }

    public function delete(FinancieelVerslag $verslag)
    {
        $this->entityManager->remove($verslag);
        $this->entityManager->flush();
    }
}
