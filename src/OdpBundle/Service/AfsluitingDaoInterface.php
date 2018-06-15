<?php

namespace OdpBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OdpBundle\Entity\Afsluiting;

interface AfsluitingDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Afsluiting
     */
    public function find($id);

    /**
     * @param Afsluiting $afsluiting
     */
    public function create(Afsluiting $afsluiting);

    /**
     * @param Afsluiting $afsluiting
     */
    public function update(Afsluiting $afsluiting);

    /**
     * @param Afsluiting $afsluiting
     */
    public function delete(Afsluiting $afsluiting);
}
