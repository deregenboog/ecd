<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\Project;
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
     * @param Project $entity
     */
    public function create(Project $entity);

    /**
     * @param Project $entity
     */
    public function update(Project $entity);

    /**
     * @param Project $entity
     */
    public function delete(Project $entity);
}
