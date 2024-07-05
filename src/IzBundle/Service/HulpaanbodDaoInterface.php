<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Hulpaanbod;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface HulpaanbodDaoInterface
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
     * @return Hulpaanbod
     */
    public function find($id);

    public function create(Hulpaanbod $entity);

    public function update(Hulpaanbod $koppeling);

    public function delete(Hulpaanbod $koppeling);
}
