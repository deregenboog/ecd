<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use MwBundle\Entity\Project;

interface ProjectDaoInterface
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
     * @return Project
     */
    public function find($id);

    public function create(Project $project);

    public function update(Project $project);

    public function delete(Project $project);
}
