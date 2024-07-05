<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Afsluiting;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface AfsluitingDaoInterface
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
     * @return Afsluiting
     */
    public function find($id);

    public function create(Afsluiting $entity);

    public function update(Afsluiting $entity);

    public function delete(Afsluiting $entity);
}
