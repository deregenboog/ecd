<?php

namespace HsBundle\Service;

use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\DeclaratieCategorie;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DeclaratieCategorieDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return DeclaratieCategorie
     */
    public function find($id);

    /**
     * @param DeclaratieCategorie $declaratieCategorie
     */
    public function create(DeclaratieCategorie $declaratieCategorie);

    /**
     * @param DeclaratieCategorie $declaratieCategorie
     */
    public function update(DeclaratieCategorie $declaratieCategorie);

    /**
     * @param DeclaratieCategorie $declaratieCategorie
     */
    public function delete(DeclaratieCategorie $declaratieCategorie);
}
