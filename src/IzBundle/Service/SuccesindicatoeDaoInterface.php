<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Afsluiting;
use Knp\Component\Pager\Pagination\PaginationInterface;
use IzBundle\Entity\Succesindicator;

interface SuccesindicatoeDaoInterface
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
     * @return Succesindicator
     */
    public function find($id);

    /**
     * @param Succesindicator $entity
     */
    public function create(Succesindicator $entity);

    /**
     * @param Succesindicator $entity
     */
    public function update(Succesindicator $entity);

    /**
     * @param Succesindicator $entity
     */
    public function delete(Succesindicator $entity);
}
