<?php

namespace OdpBundle\Service;

use OdpBundle\Entity\Document;
use Doctrine\ORM\EntityManager;

class DocumentDao implements DocumentDaoInterface
{
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Document::class);
    }

    public function findByFilename($filename)
    {
        return $this->repository->findOneByFilename($filename);
    }
}
