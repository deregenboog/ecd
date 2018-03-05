<?php

namespace IzBundle\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Hulpaanbod;

interface HulpaanbodDaoInterface
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
     * @return Hulpaanbod
     */
    public function find($id);

    /**
     * @param Hulpaanbod $entity
     */
    public function create(Hulpaanbod $entity);

    /**
     * @param Hulpaanbod $koppeling
     */
    public function update(Hulpaanbod $entity);

    /**
     * @param Hulpaanbod $koppeling
     */
    public function delete(Hulpaanbod $entity);
}
