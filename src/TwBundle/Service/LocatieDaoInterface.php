<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use TwBundle\Entity\Locatie;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface LocatieDaoInterface
{
    /**
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Locatie
     */
    public function find($id);

    /**
     * @param Locatie $locatie
     */
    public function create(Locatie $locatie);

    /**
     * @param Locatie $locatie
     */
    public function update(Locatie $locatie);

    /**
     * @param Locatie $locatie
     */
    public function delete(Locatie $locatie);
}
