<?php

namespace GaBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\KlantIntake;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface KlantIntakeDaoInterface
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
     * @param KlantIntake $entity
     */
    public function create(KlantIntake $entity);

    /**
     * @param KlantIntake $entity
     */
    public function update(KlantIntake $entity);

    /**
     * @param KlantIntake $entity
     */
    public function delete(KlantIntake $entity);
}
