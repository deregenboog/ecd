<?php

namespace UhkBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use UhkBundle\Entity\Project;

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

    public function create(Project $entity);

    public function update(Project $entity);

    public function delete(Project $entity);
}
