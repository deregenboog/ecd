<?php

namespace AppBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
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
     * @return QueryBuilder
     */
    public function getAllQueryBuilder(?FilterInterface $filter = null);

    /**
     * @return int
     */
    public function countAll(?FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Klant
     */
    public function find($id);

    public function create(Klant $klant);

    public function update(Klant $klant);

    public function delete(Klant $klant);
}
