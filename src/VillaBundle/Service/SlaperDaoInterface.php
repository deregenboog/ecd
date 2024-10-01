<?php

namespace VillaBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use VillaBundle\Entity\Slaper;

interface SlaperDaoInterface
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
     * @return Slaper
     */
    public function find($id);

    /**
     * @param Klant $klant
     *
     * @return Slaper
     */
    public function findOneByKlant(Klant $klant);

    /**
     * @param Slaper $slaper
     */
    public function create(Slaper $slaper);

    /**
     * @param Slaper $slaper
     */
    public function update(Slaper $slaper);

    /**
     * @param Slaper $slaper
     */
    public function delete(Slaper $slaper);
}
