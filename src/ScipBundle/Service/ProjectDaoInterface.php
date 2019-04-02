<?php

namespace ScipBundle\Service;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use ScipBundle\Entity\Project;

interface ProjectDaoInterface
{
    /**
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null): PaginationInterface;

    /**
     * @param Medewerker      $medewerker
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findByMedewerker(Medewerker $medewerker, $page = null, FilterInterface $filter = null): PaginationInterface;

    /**
     * @param int $id
     *
     * @return Project
     */
    public function find($id);

    /**
     * @param string $kpl
     *
     * @return Project
     */
    public function findOneByKpl(string $kpl);

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

    public function setItemsPerPage(int $itemsPerPage);
}
