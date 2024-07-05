<?php

namespace GaBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\Intake;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface IntakeDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, ?FilterInterface $filter = null);

    /**
     * @return Klant
     */
    public function findOneByKlant(Klant $klant);

    /**
     * @param int $id
     *
     * @return Klant
     */
    public function find($id);

    public function create(Intake $entity);

    public function update(Intake $entity);

    public function delete(Intake $entity);
}
