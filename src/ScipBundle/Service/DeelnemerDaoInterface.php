<?php

namespace ScipBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use ScipBundle\Entity\Deelnemer;
use ScipBundle\Entity\Document;

interface DeelnemerDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, ?FilterInterface $filter = null);

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
}
