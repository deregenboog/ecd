<?php

namespace TwBundle\Service;

use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityManagerInterface;
use TwBundle\Entity\Document;

class DocumentDao extends AbstractDao implements DocumentDaoInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Document::class);
    }

    public function find($id)
    {
        return $this->repository->find($id);
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
