<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Woonsituatie;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface WoonsituatieDaoInterface
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
     * @return Woonsituatie
     */
    public function find($id);

    public function create(Woonsituatie $entity);

    public function update(Woonsituatie $entity);

    public function delete(Woonsituatie $entity);
}
