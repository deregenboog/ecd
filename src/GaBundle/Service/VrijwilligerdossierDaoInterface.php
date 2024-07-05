<?php

namespace GaBundle\Service;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\Vrijwilligerdossier;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VrijwilligerdossierDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, ?FilterInterface $filter = null);

    /**
     * @return Vrijwilligerdossier
     */
    public function findOneByVrijwilliger(Vrijwilliger $vrijwilliger);

    /**
     * @param int $id
     *
     * @return Vrijwilligerdossier
     */
    public function find($id);

    public function create(Vrijwilligerdossier $entity);

    public function update(Vrijwilligerdossier $entity);

    public function delete(Vrijwilligerdossier $entity);
}
