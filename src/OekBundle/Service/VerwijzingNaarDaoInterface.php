<?php

namespace OekBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OekBundle\Entity\VerwijzingNaar;

interface VerwijzingNaarDaoInterface
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
     * @return VerwijzingNaar
     */
    public function find($id);

    /**
     * @param VerwijzingNaar $entity
     */
    public function create(VerwijzingNaar $entity);

    /**
     * @param VerwijzingNaar $entity
     */
    public function update(VerwijzingNaar $entity);

    /**
     * @param VerwijzingNaar $entity
     */
    public function delete(VerwijzingNaar $entity);
}
