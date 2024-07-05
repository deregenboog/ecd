<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Trajectsoort;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface TrajectsoortDaoInterface
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
     * @return Trajectsoort
     */
    public function find($id);

    public function create(Trajectsoort $trajectsoort);

    public function update(Trajectsoort $trajectsoort);

    public function delete(Trajectsoort $trajectsoort);
}
