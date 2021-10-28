<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use TwBundle\Entity\Pandeigenaar;

interface PandeigenaarDaoInterface
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
     * @return Pandeigenaar
     */
    public function find($id);

    /**
     * @param Pandeigenaar $pandeigenaar
     */
    public function create(Pandeigenaar $pandeigenaar);

    /**
     * @param Pandeigenaar $pandeigenaar
     */
    public function update(Pandeigenaar $pandeigenaar);

    /**
     * @param Pandeigenaar $pandeigenaar
     */
    public function delete(Pandeigenaar $pandeigenaar);
}
