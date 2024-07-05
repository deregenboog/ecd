<?php

namespace OekraineBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OekraineBundle\Entity\Bezoeker;
use OekraineBundle\Entity\Locatie;
use OekraineBundle\Entity\Registratie;

interface RegistratieDaoInterface
{
    public const TYPE_NIGHT = true;
    public const TYPE_DAY = false;

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

    /**
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
     * @return Registratie
     */
    public function create(Registratie $entity);

    /**
     * @return Registratie
     */
    public function update(Registratie $entity);

    /**
     * @return Registratie
     */
    public function delete(Registratie $entity);

    /**
     * @return Registratie
     */
    public function checkout(Registratie $registratie, ?\DateTime $time = null);

    public function checkoutBezoekerFromAllLocations(Bezoeker $bezoeker);
}
