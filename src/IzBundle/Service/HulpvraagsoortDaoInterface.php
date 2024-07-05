<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Hulpvraagsoort;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface HulpvraagsoortDaoInterface
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
     * @return Hulpvraagsoort
     */
    public function find($id);

    public function create(Hulpvraagsoort $hulpvraagsoort);

    public function update(Hulpvraagsoort $hulpvraagsoort);

    public function delete(Hulpvraagsoort $hulpvraagsoort);
}
