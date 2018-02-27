<?php

namespace InloopBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Registratie;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface RegistratieDaoInterface
{
    /**
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Registratie
     */
    public function find($id);

    /**
     * @param Klant   $klant
     * @param Locatie $locatie
     *
     * @return Registratie
     */
    public function findLatestByKlantAndLocatie(Klant $klant, Locatie $locatie);

    /**
     * @param Registratie $entity
     *
     * @return Registratie
     */
    public function update(Registratie $entity);

    /**
     * @param Registratie $entity
     *
     * @return Registratie
     */
    public function delete(Registratie $entity);

    /**
     * @param Registratie $entity
     *
     * @return Registratie
     */
    public function checkout(Registratie $entity);

    /**
     * @param Klant $klant
     *
     * @return void
     */
    public function checkoutKlantFromAllLocations(Klant $klant);
}
