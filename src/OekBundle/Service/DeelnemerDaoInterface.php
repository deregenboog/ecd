<?php

namespace OekBundle\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;
use AppBundle\Entity\Klant;
use OekBundle\Entity\Deelnemer;

interface DeelnemerDaoInterface
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
     * @return Deelnemer
     */
    public function find($id);

    /**
     * @param Klant $klant
     *
     * @return Deelnemer
     */
    public function findOneByKlant(Klant $klant);

    /**
     * @param Deelnemer $deelnemer
     */
    public function create(Deelnemer $deelnemer);

    /**
     * @param Deelnemer $deelnemer
     */
    public function update(Deelnemer $deelnemer);

    /**
     * @param Deelnemer $deelnemer
     */
    public function delete(Deelnemer $deelnemer);
}
