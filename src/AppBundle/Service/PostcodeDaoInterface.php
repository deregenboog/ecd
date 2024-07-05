<?php

namespace AppBundle\Service;

use AppBundle\Entity\Postcode;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface PostcodeDaoInterface
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
     * @return Postcode
     */
    public function find($id);

    public function create(Postcode $postcode);

    public function update(Postcode $postcode);

    public function delete(Postcode $postcode);
}
