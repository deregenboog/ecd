<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use TwBundle\Entity\Pandeigenaar;

interface WoningbouwcorporatieDaoInterface
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
     * @return Pandeigenaar
     */
    public function find($id);

    /**
     * @param Pandeigenaar $woningbouwcorporatie
     */
    public function create(Pandeigenaar $woningbouwcorporatie);

    /**
     * @param Pandeigenaar $woningbouwcorporatie
     */
    public function update(Pandeigenaar $woningbouwcorporatie);

    /**
     * @param Pandeigenaar $woningbouwcorporatie
     */
    public function delete(Pandeigenaar $woningbouwcorporatie);
}
