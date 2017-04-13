<?php

namespace HsBundle\Service;

use HsBundle\Entity\Dienstverlener;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;
use AppBundle\Entity\Klant;

interface DienstverlenerDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = 1, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Dienstverlener
     */
    public function find($id);

    /**
     * @param Klant $klant
     *
     * @return Dienstverlener
     */
    public function findOneByKlant(Klant $klant);

    /**
     * @param Dienstverlener $dienstverlener
     */
    public function create(Dienstverlener $dienstverlener);

    /**
     * @param Dienstverlener $dienstverlener
     */
    public function update(Dienstverlener $dienstverlener);

    /**
     * @param Dienstverlener $dienstverlener
     */
    public function delete(Dienstverlener $dienstverlener);
}
