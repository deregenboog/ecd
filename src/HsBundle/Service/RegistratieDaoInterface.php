<?php

namespace HsBundle\Service;

use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\Registratie;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface RegistratieDaoInterface
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
     * @return Registratie
     */
    public function find($id);

    public function create(Registratie $registratie);

    public function update(Registratie $registratie);

    public function delete(Registratie $registratie);

    public function countUrenByStadsdeel(?\DateTime $start = null, ?\DateTime $end = null);

    public function countUrenByGgwGebied(?\DateTime $start = null, ?\DateTime $end = null);

    public function countUrenByActiviteit(?\DateTime $start = null, ?\DateTime $end = null);

    public function countUrenByKlant(?\DateTime $start = null, ?\DateTime $end = null);

    public function countUrenByKlus(?\DateTime $start = null, ?\DateTime $end = null);

    public function countUrenByDienstverlener(?\DateTime $start = null, ?\DateTime $end = null);

    public function countUrenByVrijwilliger(?\DateTime $start = null, ?\DateTime $end = null);
}
