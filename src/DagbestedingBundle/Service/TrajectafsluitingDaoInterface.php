<?php

namespace DagbestedingBundle\Service;

use DagbestedingBundle\Entity\Trajectafsluiting;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface TrajectafsluitingDaoInterface
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
     * @return Trajectafsluiting
     */
    public function find($id);

    public function create(Trajectafsluiting $afsluiting);

    public function update(Trajectafsluiting $afsluiting);

    public function delete(Trajectafsluiting $afsluiting);
}
