<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Rapportage;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface RapportageDaoInterface
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
     * @return Rapportage
     */
    public function find($id);

    public function create(Rapportage $rapportage);

    public function update(Rapportage $rapportage);

    public function delete(Rapportage $rapportage);
}
