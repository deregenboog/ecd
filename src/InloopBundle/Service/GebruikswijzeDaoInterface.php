<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\Gebruikswijze;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface GebruikswijzeDaoInterface
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
     * @return Gebruikswijze
     */
    public function find($id);

    /**
     * @param Gebruikswijze $entity
     */
    public function create(Gebruikswijze $entity);

    /**
     * @param Gebruikswijze $entity
     */
    public function update(Gebruikswijze $entity);

    /**
     * @param Gebruikswijze $entity
     */
    public function delete(Gebruikswijze $entity);
}
