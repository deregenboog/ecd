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

    /**
     * @param Trajectcoach $Trajectcoach
     */
    public function create(Trajectcoach $Trajectcoach);

    /**
     * @param Trajectcoach $Trajectcoach
     */
    public function update(Trajectcoach $Trajectcoach);

    /**
     * @param Trajectcoach $Trajectcoach
     */
    public function delete(Trajectcoach $Trajectcoach);
}
