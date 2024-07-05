<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Communicatiekanaal;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface CommunicatiekanaalDaoInterface
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
     * @return Communicatiekanaal
     */
    public function find($id);

    public function create(Communicatiekanaal $communicatiekanaal);

    public function update(Communicatiekanaal $communicatiekanaal);

    public function delete(Communicatiekanaal $communicatiekanaal);
}
