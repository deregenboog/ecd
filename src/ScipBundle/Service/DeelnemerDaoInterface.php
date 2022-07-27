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
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param Medewerker      $medewerker
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findByMedewerker(Medewerker $medewerker, $page = null, FilterInterface $filter = null): PaginationInterface;

    /**
     * @param Klant $klant
     *
     * @return Deelnemer
     */
    public function findOneByKlant(Klant $klant);

    /**
     * @param Document $document
     *
     * @return Deelnemer
     */
    public function findOneByDocument(Document $document);

    /**
     * @param string $name
     *
     * @return Deelnemer
     */
    public function findOneByName(string $name);

    /**
     * @param int $id
     *
     * @return Deelnemer
     */
    public function find($id);

    /**
     * @param Deelnemer $entity
     */
    public function create(Deelnemer $entity);

    /**
     * @param Deelnemer $entity
     */
    public function update(Deelnemer $entity);

    /**
     * @param Deelnemer $entity
     */
    public function delete(Deelnemer $entity);
}
