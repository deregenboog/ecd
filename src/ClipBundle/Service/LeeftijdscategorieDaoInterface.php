<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Leeftijdscategorie;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface LeeftijdscategorieDaoInterface
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
     * @return Leeftijdscategorie
     */
    public function find($id);

    /**
     * @param Leeftijdscategorie $leeftijdscategorie
     */
    public function create(Leeftijdscategorie $leeftijdscategorie);

    /**
     * @param Leeftijdscategorie $leeftijdscategorie
     */
    public function update(Leeftijdscategorie $leeftijdscategorie);

    /**
     * @param Leeftijdscategorie $leeftijdscategorie
     */
    public function delete(Leeftijdscategorie $leeftijdscategorie);
}
