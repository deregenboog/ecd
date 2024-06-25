<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use TwBundle\Entity\VormVanOvereenkomst;

interface VormVanOvereenkomstDaoInterface
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
     * @return VormVanOvereenkomst
     */
    public function find($id);

    /**
     * @param VormVanOvereenkomst $entity
     */
    public function create(VormVanOvereenkomst $entity);

    /**
     * @param VormVanOvereenkomst $entity
     */
    public function update(VormVanOvereenkomst $entity);

    /**
     * @param VormVanOvereenkomst $entity
     */
    public function delete(VormVanOvereenkomst $entity);
}
