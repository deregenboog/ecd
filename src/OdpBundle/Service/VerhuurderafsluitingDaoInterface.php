<?php

namespace OdpBundle\Service;

use OdpBundle\Entity\Verhuurderafsluiting;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VerhuurderafsluitingDaoInterface
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
     * @param Coordinator $verhuurderafsluiting
     */
    public function create(Verhuurderafsluiting $verhuurderafsluiting);

    /**
     * @param Coordinator $verhuurderafsluiting
     */
    public function update(Verhuurderafsluiting $verhuurderafsluiting);

    /**
     * @param Coordinator $verhuurderafsluiting
     */
    public function delete(Verhuurderafsluiting $verhuurderafsluiting);
}
