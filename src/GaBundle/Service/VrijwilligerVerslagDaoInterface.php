<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\Verslag;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VrijwilligerVerslagDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Verslag
     */
    public function find($id);

    /**
     * @param Verslag $entity
     */
    public function create(Verslag $entity);

    /**
     * @param Verslag $entity
     */
    public function update(Verslag $entity);

    /**
     * @param Verslag $entity
     */
    public function delete(Verslag $entity);
}
