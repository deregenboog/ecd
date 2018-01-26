<?php

namespace GaBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\Klantdossier;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface KlantdossierDaoInterface
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
     * @return Klantdossier
     */
    public function findOneByKlant(Klant $klant);

    /**
     * @param int $id
     *
     * @return Klantdossier
     */
    public function find($id);

    /**
     * @param Klantdossier $entity
     */
    public function create(Klantdossier $entity);

    /**
     * @param Klantdossier $entity
     */
    public function update(Klantdossier $entity);

    /**
     * @param Klantdossier $entity
     */
    public function delete(Klantdossier $entity);
}
