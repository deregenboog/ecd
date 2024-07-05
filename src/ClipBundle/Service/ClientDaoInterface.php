<?php

namespace ClipBundle\Service;

use AppBundle\Filter\FilterInterface;
use ClipBundle\Entity\Client;
use Knp\Component\Pager\Pagination\PaginationInterface;

interface ClientDaoInterface
{
    public const FASE_BEGINSTAND = 'beginstand';
    public const FASE_GESTART = 'gestart';
    public const FASE_AFGESLOTEN = 'gestopt';
    public const FASE_EINDSTAND = 'eindstand';

    /**
     * @param int $page
     *
     * @return PaginationInterface
     */
    public function findAll($page = null, ?FilterInterface $filter = null);

    /**
     * @param int $id
     *
     * @return Client
     */
    public function find($id);

    public function create(Client $client);

    public function update(Client $client);

    public function delete(Client $client);
}
