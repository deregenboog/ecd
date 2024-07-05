<?php

namespace TwBundle\Service;

use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityManagerInterface;
use TwBundle\Entity\FinancieelDocument;

class FinancieelDocumentDao extends AbstractDao implements FinancieelDocumentDaoInterface
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(FinancieelDocument::class);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function findByFilename($filename)
    {
        return $this->repository->findOneByFilename($filename);
    }

    public function create(FinancieelDocument $document)
    {
        $this->entityManager->persist($document);
        $this->entityManager->flush();
    }

    public function update(FinancieelDocument $document)
    {
        $this->entityManager->flush();
    }

    public function delete(FinancieelDocument $document)
    {
        $this->entityManager->remove($document);
        $this->entityManager->flush();
    }
}
