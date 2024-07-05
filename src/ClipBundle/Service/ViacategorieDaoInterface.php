<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Viacategorie;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ViacategorieDaoInterface
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
     * @return Viacategorie
     */
    public function find($id);

    public function create(Viacategorie $viacategorie);

    public function update(Viacategorie $viacategorie);

    public function delete(Viacategorie $viacategorie);
}
