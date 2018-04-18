<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Hulpvraagsoort;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface HulpvraagsoortDaoInterface
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
     * @return Hulpvraagsoort
     */
    public function find($id);

    /**
     * @param Hulpvraagsoort $hulpvraagsoort
     */
    public function create(Hulpvraagsoort $hulpvraagsoort);

    /**
     * @param Hulpvraagsoort $hulpvraagsoort
     */
    public function update(Hulpvraagsoort $hulpvraagsoort);

    /**
     * @param Hulpvraagsoort $hulpvraagsoort
     */
    public function delete(Hulpvraagsoort $hulpvraagsoort);
}
