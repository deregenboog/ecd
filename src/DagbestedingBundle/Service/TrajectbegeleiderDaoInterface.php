<?php

namespace DagbestedingBundle\Service;

use DagbestedingBundle\Entity\Trajectbegeleider;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface TrajectbegeleiderDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null);

    /**
     * @param int $id
     *
     * @return Trajectbegeleider
     */
    public function find($id);

    public function create(Trajectbegeleider $trajectbegeleider);

    public function update(Trajectbegeleider $trajectbegeleider);

    public function delete(Trajectbegeleider $trajectbegeleider);
}
