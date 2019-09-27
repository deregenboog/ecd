<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Infobaliedoelgroep;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface InfobaliedoelgroepDaoInterface
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
     * @return Infobaliedoelgroep
     */
    public function find($id);

    /**
     * @param Infobaliedoelgroep $entity
     */
    public function create(Infobaliedoelgroep $entity);

    /**
     * @param Infobaliedoelgroep $entity
     */
    public function update(Infobaliedoelgroep $entity);

    /**
     * @param Infobaliedoelgroep $entity
     */
    public function delete(Infobaliedoelgroep $entity);
}
