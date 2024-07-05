<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use TwBundle\Entity\VormVanOvereenkomst;

interface VormVanOvereenkomstDaoInterface
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
     * @return VormVanOvereenkomst
     */
    public function find($id);

    public function create(VormVanOvereenkomst $entity);

    public function update(VormVanOvereenkomst $entity);

    public function delete(VormVanOvereenkomst $entity);
}
