<?php

namespace InloopBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Registratie;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface RegistratieDaoInterface
{
    public const TYPE_NIGHT = true;
    public const TYPE_DAY = false;

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
     * @param bool $type the value of either self::TYPE_DAY of self::TYPE_NIGHT
     *
     * @return Registratie[]
     */
    public function findAutoCheckoutCandidates($type);

    /**
     * @param Registratie $entity
     *
     * @return Registratie
     */
    public function create(Registratie $entity);

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
     * @param \DateTime   $time
     *
     * @return Registratie
     */
    public function checkout(Registratie $registratie, \DateTime $time = null);

    /**
     * @param Klant $klant
     */
    public function checkoutKlantFromAllLocations(Klant $klant);
}
