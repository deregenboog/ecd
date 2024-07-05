<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Locatie;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface LocatieDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, ?FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Locatie
     */
    public function find($id);

    public function create(Locatie $locatie);

    public function update(Locatie $locatie);

    public function delete(Locatie $locatie);
}
