<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Client;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ClientDaoInterface
{
    const FASE_BEGINSTAND = 'beginstand';
    const FASE_GESTART = 'gestart';
    const FASE_AFGESLOTEN = 'gestopt';
    const FASE_EINDSTAND = 'eindstand';

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
     * @return Client
     */
    public function find($id);

    /**
     * @param Client $client
     */
    public function create(Client $client);

    /**
     * @param Client $client
     */
    public function update(Client $client);

    /**
     * @param Client $client
     */
    public function delete(Client $client);

    public function countByBegeleider($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByLocatie($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByProject($fase, \DateTime $startdate, \DateTime $enddate);

    public function countByResultaatgebiedsoort($fase, \DateTime $startdate, \DateTime $enddate);
}
