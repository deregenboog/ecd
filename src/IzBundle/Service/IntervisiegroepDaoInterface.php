<?php

namespace IzBundle\Service;

use IzBundle\Entity\Intervisiegroep;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;

interface IntervisiegroepDaoInterface
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
     * @return Intervisiegroep
     */
    public function find($id);

    /**
     * @param Intervisiegroep $entity
     */
    public function create(Intervisiegroep $entity);

    /**
     * @param Intervisiegroep $entity
     */
    public function update(Intervisiegroep $entity);

    /**
     * @param Intervisiegroep $entity
     */
    public function delete(Intervisiegroep $entity);
}
