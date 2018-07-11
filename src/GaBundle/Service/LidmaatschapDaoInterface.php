<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\Groep;
use GaBundle\Entity\Lidmaatschap;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface LidmaatschapDaoInterface
{
    /**
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param Groep $groep
     * @param int   $page
     *
     * @return PaginationInterface
     */
    public function findByGroep(Groep $entity);

    /**
     * @param int $id
     *
     * @return Lidmaatschap
     */
    public function find($id);

    /**
     * @param Lidmaatschap $entity
     */
    public function create(Lidmaatschap $entity);

    /**
     * @param Lidmaatschap $entity
     */
    public function update(Lidmaatschap $entity);

    /**
     * @param Lidmaatschap $entity
     */
    public function delete(Lidmaatschap $entity);
}
