<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Verslag;
use Doctrine\ORM\EntityManagerInterface;

class VerslagDao extends AbstractDao implements VerslagDaoInterface
{
    protected $class = Verslag::class;
    protected $alias = 'verslag';

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository($this->class);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function create(Verslag $verslag)
    {
        $this->entityManager->persist($verslag);
        $this->entityManager->flush();
    }

    public function update(Verslag $verslag)
    {
        $this->entityManager->flush();
    }

    public function delete(Verslag $verslag)
    {
        $this->entityManager->remove($verslag);
        $this->entityManager->flush();
    }
}
