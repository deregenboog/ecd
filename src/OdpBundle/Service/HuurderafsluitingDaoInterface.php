<?php

namespace OdpBundle\Service;

use OdpBundle\Entity\Huurderafsluiting;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface HuurderafsluitingDaoInterface
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
     * @return Coordinator
     */
    public function find($id);

    /**
     * @param Coordinator $huurderafsluiting
     */
    public function create(Huurderafsluiting $huurderafsluiting);

    /**
     * @param Coordinator $huurderafsluiting
     */
    public function update(Huurderafsluiting $huurderafsluiting);

    /**
     * @param Coordinator $huurderafsluiting
     */
    public function delete(Huurderafsluiting $huurderafsluiting);
}
