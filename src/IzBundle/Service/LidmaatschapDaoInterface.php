<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Lidmaatschap;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface LidmaatschapDaoInterface
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
     * @return Lidmaatschap
     */
    public function find($id);

    public function create(Lidmaatschap $entity);

    public function update(Lidmaatschap $entity);

    public function delete(Lidmaatschap $entity);
}
