<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use MwBundle\Entity\Project;
use Knp\Component\Pager\Pagination\PaginationInterface;

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

    /**
     * @param Project $project
     */
    public function create(Project $project);

    /**
     * @param Project $project
     */
    public function update(Project $project);

    /**
     * @param Project $project
     */
    public function delete(Project $project);
}
