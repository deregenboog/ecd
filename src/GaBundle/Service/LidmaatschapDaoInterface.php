<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\GaGroep;
use GaBundle\Entity\GaLidmaatschap;
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
    public function findByGroep(GaGroep $entity);

    /**
     * @param int $id
     *
     * @return Lidmaatschap
     */
    public function find($id);

    /**
     * @param GaLidmaatschap $entity
     */
    public function create(GaLidmaatschap $entity);

    /**
     * @param GaLidmaatschap $entity
     */
    public function update(GaLidmaatschap $entity);

    /**
     * @param GaLidmaatschap $entity
     */
    public function delete(GaLidmaatschap $entity);
}
