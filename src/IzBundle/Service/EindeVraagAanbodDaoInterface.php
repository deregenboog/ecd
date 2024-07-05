<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\EindeVraagAanbod;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface EindeVraagAanbodDaoInterface
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
     * @return EindeVraagAanbod
     */
    public function find($id);

    public function create(EindeVraagAanbod $entity);

    public function update(EindeVraagAanbod $entity);

    public function delete(EindeVraagAanbod $entity);
}
