<?php

namespace DagbestedingBundle\Service;

use DagbestedingBundle\Entity\Trajectcoach;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface TrajectcoachDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null);

    /**
     * @param int $id
     *
     * @return Trajectcoach
     */
    public function find($id);

    public function create(Trajectcoach $Trajectcoach);

    public function update(Trajectcoach $Trajectcoach);

    public function delete(Trajectcoach $Trajectcoach);
}
