<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use TwBundle\Entity\Afsluiting;

interface AfsluitingDaoInterface
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
     * @return Afsluiting
     */
    public function find($id);

    public function create(Afsluiting $afsluiting);

    public function update(Afsluiting $afsluiting);

    public function delete(Afsluiting $afsluiting);
}
