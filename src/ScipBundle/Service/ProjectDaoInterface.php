<?php

namespace ScipBundle\Service;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use ScipBundle\Entity\Project;

interface ProjectDaoInterface
{
    /**
     * @param int $page
     */
    public function findAll($page = null, ?FilterInterface $filter = null): PaginationInterface;

    /**
     * @param int $page
     */
    public function findByMedewerker(Medewerker $medewerker, $page = null, ?FilterInterface $filter = null): PaginationInterface;

    /**
     * @param int $id
     *
     * @return Project
     */
    public function find($id);

    /**
     * @return Project
     */
    public function findOneByKpl(string $kpl);

    public function create(Project $entity);

    public function update(Project $entity);

    public function delete(Project $entity);

    public function setItemsPerPage(int $itemsPerPage);
}
