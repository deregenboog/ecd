<?php

namespace ErOpUitBundle\Service;

use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Filter\FilterInterface;
use ErOpUitBundle\Entity\Klant;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface KlantDaoInterface
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
     * @return Klant
     */
    public function find($id);

    /**
     * @param AppKlant $appKlant
     *
     * @return Klant
     */
    public function findOneByKlant(AppKlant $appKlant);

    /**
     * @param Klant $klant
     */
    public function create(Klant $klant);

    /**
     * @param Klant $klant
     */
    public function update(Klant $klant);

    /**
     * @param Klant $klant
     */
    public function delete(Klant $klant);
}
