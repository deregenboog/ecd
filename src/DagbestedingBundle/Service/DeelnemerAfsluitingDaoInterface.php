<?php

namespace DagbestedingBundle\Service;

use Knp\Component\Pager\Pagination\PaginationInterface;
use DagbestedingBundle\Entity\DeelnemerAfsluiting;

interface DeelnemerAfsluitingDaoInterface
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
     * @return DeelnemerAfsluiting
     */
    public function find($id);

    /**
     * @param DeelnemerAfsluiting $afsluiting
     */
    public function create(DeelnemerAfsluiting $afsluiting);

    /**
     * @param DeelnemerAfsluiting $afsluiting
     */
    public function update(DeelnemerAfsluiting $afsluiting);

    /**
     * @param DeelnemerAfsluiting $afsluiting
     */
    public function delete(DeelnemerAfsluiting $afsluiting);
}
