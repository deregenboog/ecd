<?php

namespace OdpBundle\Service;

use AppBundle\Filter\FilterInterface;
use OdpBundle\Entity\Training;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface TrainingDaoInterface
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
     * @return Training
     */
    public function find($id);

    /**
     * @param Training $vwtraining
     */
    public function create(Training $vwtraining);

    /**
     * @param Training $vwtraining
     */
    public function update(Training $vwtraining);

    /**
     * @param Training $vwtraining
     */
    public function delete(Training $vwtraining);
}
