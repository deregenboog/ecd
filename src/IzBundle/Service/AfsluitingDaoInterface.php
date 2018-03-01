<?php

namespace IzBundle\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\EindeKoppeling;
use IzBundle\Entity\IzAfsluiting;

interface AfsluitingDaoInterface
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
     * @return IzAfsluiting
     */
    public function find($id);

    /**
     * @param IzAfsluiting $entity
     */
    public function create(IzAfsluiting $entity);

    /**
     * @param IzAfsluiting $entity
     */
    public function update(IzAfsluiting $entity);

    /**
     * @param IzAfsluiting $entity
     */
    public function delete(IzAfsluiting $entity);
}
