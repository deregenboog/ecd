<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use InloopBundle\Entity\Schorsing;

interface SchorsingDaoInterface
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
     * @return Schorsing
     */
    public function find($id);

    /**
     * @param Schorsing
     *
     * @return Schorsing
     */
    public function create(Schorsing $entity);

    /**
     * @param Schorsing
     *
     * @return Schorsing
     */
    public function update(Schorsing $entity);


    /**
     * @param Schorsing
     *
     * @return Schorsing
     */
    public function delete(Schorsing $entity);
}
