<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\Dossier;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DossierDaoInterface
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
     * @return Dossier
     */
    public function find($id);

    /**
     * @param Dossier $entity
     */
    public function update(Dossier $entity);

    /**
     * @param Dossier $entity
     */
    public function delete(Dossier $entity);
}
