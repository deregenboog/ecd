<?php

namespace IzBundle\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\EindeKoppeling;

interface EindeKoppelingDaoInterface
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
     * @return EindeKoppeling
     */
    public function find($id);

    /**
     * @param EindeKoppeling $entity
     */
    public function create(EindeKoppeling $entity);

    /**
     * @param EindeKoppeling $entity
     */
    public function update(EindeKoppeling $entity);

    /**
     * @param EindeKoppeling $entity
     */
    public function delete(EindeKoppeling $entity);
}
