<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Leeftijdscategorie;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface LeeftijdscategorieDaoInterface
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
     * @return Leeftijdscategorie
     */
    public function find($id);

    public function create(Leeftijdscategorie $leeftijdscategorie);

    public function update(Leeftijdscategorie $leeftijdscategorie);

    public function delete(Leeftijdscategorie $leeftijdscategorie);
}
