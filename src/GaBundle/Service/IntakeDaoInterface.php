<?php

namespace GaBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\Intake;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface IntakeDaoInterface
{
    /**
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param Klant $klant
     *
     * @return Klant
     */
    public function findOneByKlant(Klant $klant);

    /**
     * @param int $id
     *
     * @return Klant
     */
    public function find($id);

    /**
     * @param Intake $entity
     */
    public function create(Intake $entity);

    /**
     * @param Intake $entity
     */
    public function update(Intake $entity);

    /**
     * @param Intake $entity
     */
    public function delete(Intake $entity);
}
