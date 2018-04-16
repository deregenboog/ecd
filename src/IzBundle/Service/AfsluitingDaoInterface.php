<?php

namespace IzBundle\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Afsluiting;

interface AfsluitingDaoInterface
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
     * @return Afsluiting
     */
    public function find($id);

    /**
     * @param Afsluiting $entity
     */
    public function create(Afsluiting $entity);

    /**
     * @param Afsluiting $entity
     */
    public function update(Afsluiting $entity);

    /**
     * @param Afsluiting $entity
     */
    public function delete(Afsluiting $entity);
}
