<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\Lidmaatschap;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface LidmaatschapDaoInterface
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

    public function create(Lidmaatschap $entity);

    public function update(Lidmaatschap $entity);

    public function delete(Lidmaatschap $entity);
}
