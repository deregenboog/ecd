<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use GaBundle\Entity\KlantDeelname;
use GaBundle\Entity\VrijwilligerDeelname;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface VrijwilligerDeelnameDaoInterface
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
     * @return KlantDeelname
     */
    public function find($id);

    /**
     * @param VrijwilligerDeelname $entity
     */
    public function create(VrijwilligerDeelname $entity);

    /**
     * @param VrijwilligerDeelname $entity
     */
    public function update(VrijwilligerDeelname $entity);

    /**
     * @param VrijwilligerDeelname $entity
     */
    public function delete(VrijwilligerDeelname $entity);
}
