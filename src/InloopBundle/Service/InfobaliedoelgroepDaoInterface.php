<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Infobaliedoelgroep;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface InfobaliedoelgroepDaoInterface
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
     * @return Infobaliedoelgroep
     */
    public function find($id);

    public function create(Infobaliedoelgroep $entity);

    public function update(Infobaliedoelgroep $entity);

    public function delete(Infobaliedoelgroep $entity);
}
