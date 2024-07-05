<?php

namespace IzBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\IzKlant;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface KlantDaoInterface
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
     * @return IzKlant
     */
    public function find($id);

    /**
     * @return IzKlant
     */
    public function findOneByKlant(Klant $klant);

    public function create(IzKlant $klant);

    public function update(IzKlant $klant);

    public function delete(IzKlant $klant);
}
