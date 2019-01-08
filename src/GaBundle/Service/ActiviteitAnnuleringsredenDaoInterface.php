<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\ActiviteitAnnuleringsreden;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ActiviteitAnnuleringsredenDaoInterface
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
     * @return ActiviteitAnnuleringsreden
     */
    public function find($id);

    /**
     * @param ActiviteitAnnuleringsreden $entity
     */
    public function create(ActiviteitAnnuleringsreden $entity);

    /**
     * @param ActiviteitAnnuleringsreden $entity
     */
    public function update(ActiviteitAnnuleringsreden $entity);

    /**
     * @param ActiviteitAnnuleringsreden $entity
     */
    public function delete(ActiviteitAnnuleringsreden $entity);
}
