<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use IzBundle\Entity\ContactOntstaan;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ContactOntstaanDaoInterface
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
     * @return ContactOntstaan
     */
    public function find($id);

    /**
     * @param ContactOntstaan $entity
     */
    public function create(ContactOntstaan $entity);

    /**
     * @param ContactOntstaan $entity
     */
    public function update(ContactOntstaan $entity);

    /**
     * @param ContactOntstaan $entity
     */
    public function delete(ContactOntstaan $entity);
}
