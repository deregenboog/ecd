<?php

namespace UhkBundle\Service;

use AppBundle\Service\AbstractDao;
use Doctrine\ORM\EntityManagerInterface;
use UhkBundle\Entity\Document;

class DocumentDao extends AbstractDao implements DocumentDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'document.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'document.id',
            'document.naam',
        ],
        'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = Document::class;

    protected $alias = 'document';

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
