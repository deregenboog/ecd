<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Intervisiegroep;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface IntervisiegroepDaoInterface
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
     * @return Intervisiegroep
     */
    public function find($id);

    public function create(Intervisiegroep $entity);

    public function update(Intervisiegroep $entity);

    public function delete(Intervisiegroep $entity);
}
