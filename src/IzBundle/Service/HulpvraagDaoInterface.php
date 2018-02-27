<?php

namespace IzBundle\Service;

use IzBundle\Entity\IzHulpvraag;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;

interface HulpvraagDaoInterface
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
     * @return IzHulpvraag
     */
    public function find($id);

    /**
     * @param IzHulpvraag $entity
     */
    public function create(IzHulpvraag $entity);

    /**
     * @param IzHulpvraag $entity
     */
    public function update(IzHulpvraag $entity);

    /**
     * @param IzHulpvraag $entity
     */
    public function delete(IzHulpvraag $entity);
}
