<?php

namespace OekraineBundle\Service;

use AppBundle\Filter\FilterInterface;
use OekraineBundle\Entity\Training;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface TrainingDaoInterface
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
     * @return Training
     */
    public function find($id);

    public function create(Training $vwtraining);

    public function update(Training $vwtraining);

    public function delete(Training $vwtraining);
}
