<?php

namespace GaBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface KlantDaoInterface
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
     * @return Klant
     */
    public function find($id);

    public function update(Klant $entity);

    public function delete(Klant $entity);
}
