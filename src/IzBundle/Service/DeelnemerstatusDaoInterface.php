<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Deelnemerstatus;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DeelnemerstatusDaoInterface
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
     * @return Deelnemerstatus
     */
    public function find($id);

    /**
     * @param Deelnemerstatus $entity
     */
    public function create(Deelnemerstatus $entity);

    /**
     * @param Deelnemerstatus $entity
     */
    public function update(Deelnemerstatus $entity);

    /**
     * @param Deelnemerstatus $entity
     */
    public function delete(Deelnemerstatus $entity);
}
