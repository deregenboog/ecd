<?php

namespace OekBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OekBundle\Entity\Deelnemer;

interface DeelnemerDaoInterface
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
     * @return Deelnemer
     */
    public function find($id);

    /**
     * @return Deelnemer
     */
    public function findOneByKlant(Klant $klant);

    public function create(Deelnemer $deelnemer);

    public function update(Deelnemer $deelnemer);

    public function delete(Deelnemer $deelnemer);
}
