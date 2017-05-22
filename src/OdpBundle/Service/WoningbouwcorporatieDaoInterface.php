<?php

namespace OdpBundle\Service;

use OdpBundle\Entity\Woningbouwcorporatie;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface WoningbouwcorporatieDaoInterface
{
    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = 1);

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
