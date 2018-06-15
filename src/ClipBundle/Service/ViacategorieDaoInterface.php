<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Viacategorie;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ViacategorieDaoInterface
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
     * @return Viacategorie
     */
    public function find($id);

    /**
     * @param Viacategorie $viacategorie
     */
    public function create(Viacategorie $viacategorie);

    /**
     * @param Viacategorie $viacategorie
     */
    public function update(Viacategorie $viacategorie);

    /**
     * @param Viacategorie $viacategorie
     */
    public function delete(Viacategorie $viacategorie);
}
