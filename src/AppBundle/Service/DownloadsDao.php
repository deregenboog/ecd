<?php

namespace AppBundle\Service;

use AppBundle\Entity\Document;
use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DownloadsDao
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var EntityRepository
     */
    protected $repository;

    public function __construct(EntityManagerInterface $entityManager, iterable $taggedExports)
    {
        foreach ($taggedExports as $id => $export)
        {
            $export->setServiceId($id);
        }

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
