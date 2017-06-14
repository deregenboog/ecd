<?php

namespace DagbestedingBundle\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;
use DagbestedingBundle\Entity\Trajectafsluiting;

interface TrajectafsluitingDaoInterface
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
     * @return Trajectafsluiting
     */
    public function find($id);

    /**
     * @param Trajectafsluiting $afsluiting
     */
    public function create(Trajectafsluiting $afsluiting);

    /**
     * @param Trajectafsluiting $afsluiting
     */
    public function update(Trajectafsluiting $afsluiting);

    /**
     * @param Trajectafsluiting $afsluiting
     */
    public function delete(Trajectafsluiting $afsluiting);
}
