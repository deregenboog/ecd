<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\GaGroep;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface GroepDaoInterface
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
     * @return GaGroep
     */
    public function find($id);

    /**
     * @param GaGroep $entity
     */
    public function create(GaGroep $entity);

    /**
     * @param GaGroep $entity
     */
    public function update(GaGroep $entity);

    /**
     * @param GaGroep $entity
     */
    public function delete(GaGroep $entity);
}
