<?php

namespace GaBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VrijwilligerDaoInterface
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
     * @return Klant
     */
    public function find($id);

    /**
     * @param Klant $entity
     */
    public function create(Vrijwilliger $entity);

    /**
     * @param Klant $entity
     */
    public function update(Vrijwilliger $entity);

    /**
     * @param Klant $entity
     */
    public function delete(Vrijwilliger $entity);
}
