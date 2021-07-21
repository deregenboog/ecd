<?php

namespace AppBundle\Service;

use AppBundle\Entity\Document;
use AppBundle\Exception\AppException;
use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\EntityManager;

class DocumentDao implements DocumentDaoInterface
{
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Document::class);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function findAll($page = null, FilterInterface $filter = null)
    {
      throw new AppException("No findAll on DocumentDao. Who called me........?");
    }
    public function findByFilename($filename)
    {
        return $this->repository->findOneByFilename($filename);
    }

    public function create(Document $document)
    {
        $this->entityManager->persist($document);
        $this->entityManager->flush();
    }

    public function update(Document $document)
    {
        $this->entityManager->flush();
    }

    public function delete(Document $document)
    {
        $this->entityManager->remove($document);
        $this->entityManager->flush();
    }
}
