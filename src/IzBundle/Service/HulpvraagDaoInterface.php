<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Hulpvraag;
use Knp\Component\Pager\Pagination\PaginationInterface;

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
     * @return Hulpvraag
     */
    public function find($id);

    /**
     * @param Hulpvraag $entity
     */
    public function create(Hulpvraag $entity);

    /**
     * @param Hulpvraag $entity
     */
    public function update(Hulpvraag $entity);

    /**
     * @param Hulpvraag $entity
     */
    public function delete(Hulpvraag $entity);
}
