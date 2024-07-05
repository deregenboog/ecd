<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use DagbestedingBundle\Entity\Project;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ProjectDaoInterface
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
     * @return Project
     */
    public function find($id);

    public function create(Project $project);

    public function update(Project $project);

    public function delete(Project $project);
}
