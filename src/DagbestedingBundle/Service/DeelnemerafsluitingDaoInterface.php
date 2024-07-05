<?php

namespace DagbestedingBundle\Service;

use DagbestedingBundle\Entity\Deelnemerafsluiting;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DeelnemerafsluitingDaoInterface
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
     * @return Deelnemerafsluiting
     */
    public function find($id);

    public function create(Deelnemerafsluiting $afsluiting);

    public function update(Deelnemerafsluiting $afsluiting);

    public function delete(Deelnemerafsluiting $afsluiting);
}
