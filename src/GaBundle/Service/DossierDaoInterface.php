<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\Dossier;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface DossierDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, ?FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Dossier
     */
    public function find($id);

    public function update(Dossier $entity);

    public function delete(Dossier $entity);
}
