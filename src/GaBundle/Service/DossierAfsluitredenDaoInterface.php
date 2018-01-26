<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\ActiviteitAnnuleringsreden;
use GaBundle\Entity\DossierAfsluitreden;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DossierAfsluitredenDaoInterface
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
     * @return DossierAfsluitreden
     */
    public function find($id);

    /**
     * @param DossierAfsluitreden $entity
     */
    public function create(DossierAfsluitreden $entity);

    /**
     * @param ActiviteitAnnuleringsreden $entity
     */
    public function update(DossierAfsluitreden $entity);

    /**
     * @param ActiviteitAnnuleringsreden $entity
     */
    public function delete(DossierAfsluitreden $entity);
}
