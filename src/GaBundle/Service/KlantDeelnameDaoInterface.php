<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\KlantDeelname;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface KlantDeelnameDaoInterface
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
     * @return KlantDeelname
     */
    public function find($id);

    /**
     * @param KlantDeelname $entity
     */
    public function create(KlantDeelname $entity);

    /**
     * @param KlantDeelname $entity
     */
    public function update(KlantDeelname $entity);

    /**
     * @param KlantDeelname $entity
     */
    public function delete(KlantDeelname $entity);
}
