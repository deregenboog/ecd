<?php

namespace DagbestedingBundle\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;
use DagbestedingBundle\Entity\Deelnemerafsluiting;

interface DeelnemerafsluitingDaoInterface
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
     * @return Deelnemerafsluiting
     */
    public function find($id);

    /**
     * @param Deelnemerafsluiting $afsluiting
     */
    public function create(Deelnemerafsluiting $afsluiting);

    /**
     * @param Deelnemerafsluiting $afsluiting
     */
    public function update(Deelnemerafsluiting $afsluiting);

    /**
     * @param Deelnemerafsluiting $afsluiting
     */
    public function delete(Deelnemerafsluiting $afsluiting);
}
