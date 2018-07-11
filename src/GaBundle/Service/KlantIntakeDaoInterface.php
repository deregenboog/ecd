<?php

namespace GaBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\GaKlantIntake;
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
     * @param GaKlantIntake $entity
     */
    public function create(GaKlantIntake $entity);

    /**
     * @param GaKlantIntake $entity
     */
    public function update(GaKlantIntake $entity);

    /**
     * @param GaKlantIntake $entity
     */
    public function delete(GaKlantIntake $entity);
}
