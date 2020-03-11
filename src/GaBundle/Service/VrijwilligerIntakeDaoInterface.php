<?php

namespace GaBundle\Service;

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\VrijwilligerIntake;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VrijwilligerIntakeDaoInterface
{
    /**
     * @param int             $page
     * @param FilterInterface $filter
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @return VrijwilligerIntake
     */
    public function findOneByVrijwilliger(Vrijwilliger $vrijwilliger);

    /**
     * @param int $id
     *
     * @return VrijwilligerIntake
     */
    public function find($id);

    public function create(VrijwilligerIntake $entity);

    public function update(VrijwilligerIntake $entity);

    public function delete(VrijwilligerIntake $entity);
}
