<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Contactpersoon;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ContactpersoonDaoInterface
{
    /**
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Contactpersoon
     */
    public function find($id);

    /**
     * @param Contactpersoon $contactpersoon
     */
    public function create(Contactpersoon $contactpersoon);

    /**
     * @param Contactpersoon $contactpersoon
     */
    public function update(Contactpersoon $contactpersoon);

    /**
     * @param Contactpersoon $contactpersoon
     */
    public function delete(Contactpersoon $contactpersoon);
}
