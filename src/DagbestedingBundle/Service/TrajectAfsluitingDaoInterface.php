<?php

namespace DagbestedingBundle\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;
use DagbestedingBundle\Entity\TrajectAfsluiting;

interface TrajectAfsluitingDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = 1);

    /**
     * @param int $id
     *
     * @return TrajectAfsluiting
     */
    public function find($id);

    /**
     * @param TrajectAfsluiting $afsluiting
     */
    public function create(TrajectAfsluiting $afsluiting);

    /**
     * @param TrajectAfsluiting $afsluiting
     */
    public function update(TrajectAfsluiting $afsluiting);

    /**
     * @param TrajectAfsluiting $afsluiting
     */
    public function delete(TrajectAfsluiting $afsluiting);
}
