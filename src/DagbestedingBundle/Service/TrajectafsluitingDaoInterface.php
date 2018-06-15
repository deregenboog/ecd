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
