<?php

namespace IzBundle\Service;

use IzBundle\Entity\EindeVraagAanbod;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;

interface EindeVraagAanbodDaoInterface
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
     * @return EindeVraagAanbod
     */
    public function find($id);

    /**
     * @param EindeVraagAanbod $entity
     */
    public function create(EindeVraagAanbod $entity);

    /**
     * @param EindeVraagAanbod $entity
     */
    public function update(EindeVraagAanbod $entity);

    /**
     * @param EindeVraagAanbod $entity
     */
    public function delete(EindeVraagAanbod $entity);
}
