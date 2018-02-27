<?php

namespace IzBundle\Service;

use IzBundle\Entity\IzHulpvraag;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\IzHulpaanbod;

interface HulpaanbodDaoInterface
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
     * @return IzHulpaanbod
     */
    public function find($id);

    /**
     * @param IzHulpaanbod $entity
     */
    public function create(IzHulpaanbod $entity);

    /**
     * @param IzHulpaanbod $koppeling
     */
    public function update(IzHulpaanbod $entity);

    /**
     * @param IzHulpaanbod $koppeling
     */
    public function delete(IzHulpaanbod $entity);
}
