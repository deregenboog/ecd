<?php

namespace ScipBundle\Service;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use ScipBundle\Entity\Toegangsrecht;

interface ToegangsrechtDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, FilterInterface $filter = null);

    /**
     * @param Medewerker $medewerker
     *
     * @return Toegangsrecht
     */
    public function findOneByMedewerker(Medewerker $medewerker): ?Toegangsrecht;

    /**
     * @param int $id
     *
     * @return Toegangsrecht
     */
    public function find($id);

    /**
     * @param Toegangsrecht $entity
     */
    public function create(Toegangsrecht $entity);

    /**
     * @param Toegangsrecht $entity
     */
    public function update(Toegangsrecht $entity);

    /**
     * @param Toegangsrecht $entity
     */
    public function delete(Toegangsrecht $entity);
}
