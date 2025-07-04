<?php

namespace UhkBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use UhkBundle\Entity\Deelnemer;
use UhkBundle\Entity\Document;

interface DeelnemerDaoInterface
{
    /**
     * @param int $page
     */
    public function findAll($page = null, ?FilterInterface $filter = null): PaginationInterface;

    /**
     * @param int $page
     */
    public function findByMedewerker(Medewerker $medewerker, $page = null, ?FilterInterface $filter = null): PaginationInterface;

    /**
     * @return Deelnemer
     */
    public function findOneByKlant(Klant $klant);

    /**
     * @return Deelnemer
     */
    public function findOneByDocument(Document $document);

    /**
     * @return Deelnemer
     */
    public function findOneByName(string $name);

    /**
     * @param int $id
     *
     * @return Deelnemer
     */
    public function find($id);

    public function create(Deelnemer $entity);

    public function update(Deelnemer $entity);

    public function delete(Deelnemer $entity);

    /**
     * @param int $projectId
     *
     * @return int
     */
    public function countByProjectId(int $projectId): int;
}
