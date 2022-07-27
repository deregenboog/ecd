<?php

namespace OekraineBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OekraineBundle\Entity\Bezoeker;

interface BezoekerDaoInterface
{
    /**
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param FilterInterface $filter
     *
     * @return QueryBuilder
     */
    public function getAllQueryBuilder(FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Bezoeker
     */
    public function find($id);

    /**
     * @param Bezoeker $entity
     *
     * @return Bezoeker
     */
    public function create(Bezoeker $entity);

    /**
     * @param Bezoeker $entity
     *
     * @return Bezoeker
     */
    public function update(Bezoeker $entity);
}
