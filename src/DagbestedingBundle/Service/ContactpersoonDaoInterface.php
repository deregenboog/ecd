<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Contactpersoon;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ContactpersoonDaoInterface
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
     * @return Contactpersoon
     */
    public function find($id);

    public function create(Contactpersoon $contactpersoon);

    public function update(Contactpersoon $contactpersoon);

    public function delete(Contactpersoon $contactpersoon);
}
