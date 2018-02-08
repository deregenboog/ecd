<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use InloopBundle\Entity\Registratie;

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
     * @param Registratie $entity
     *
     * @return Registratie
     */
    public function update(Registratie $entity);
}
