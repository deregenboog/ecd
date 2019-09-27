<?php

namespace OdpBundle\Service;

use AppBundle\Filter\FilterInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OdpBundle\Entity\Woningbouwcorporatie;

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
     * @return Woningbouwcorporatie
     */
    public function find($id);

    /**
     * @param Woningbouwcorporatie $woningbouwcorporatie
     */
    public function create(Woningbouwcorporatie $woningbouwcorporatie);

    /**
     * @param Woningbouwcorporatie $woningbouwcorporatie
     */
    public function update(Woningbouwcorporatie $woningbouwcorporatie);

    /**
     * @param Woningbouwcorporatie $woningbouwcorporatie
     */
    public function delete(Woningbouwcorporatie $woningbouwcorporatie);
}
