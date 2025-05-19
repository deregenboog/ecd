<?php

namespace InloopBundle\Service;

use AppBundle\Filter\FilterInterface;
use InloopBundle\Entity\AccessFields;
use InloopBundle\Entity\Intake;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface AccessFieldsDaoInterface
{
    /**
     * @param int $id
     *
     * @return AccessFields
     */
    public function find($id);

    /**
     * @return AccessFields
     */
    public function update(AccessFields $entity);
}
