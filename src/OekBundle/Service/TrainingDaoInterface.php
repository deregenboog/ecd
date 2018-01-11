<?php

namespace OekBundle\Service;

use OekBundle\Entity\Dienstverlener;
use Knp\Component\Pager\Pagination\PaginationInterface;
use AppBundle\Filter\FilterInterface;
use AppBundle\Entity\Klant;
use OekBundle\Entity\Deelnemer;
use OekBundle\Entity\Training;

interface TrainingDaoInterface
{
    /**
     * @param int $page
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
     * @param Training $entity
     */
    public function create(Training $entity);

    /**
     * @param Training $entity
     */
    public function update(Training $entity);

    /**
     * @param Training $entity
     */
    public function delete(Training $entity);
}
