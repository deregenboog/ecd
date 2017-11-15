<?php

namespace ClipBundle\Service;

use AppBundle\Service\AbstractDao;
use ClipBundle\Entity\Hulpvrager;

class HulpvragerDao extends AbstractDao implements HulpvragerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'hulpvrager.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'hulpvrager.id',
            'hulpvrager.naam',
            'hulpvrager.actief',
        ],
    ];

    protected $class = Hulpvrager::class;

    protected $alias = 'hulpvrager';

    public function create(Hulpvrager $hulpvrager)
    {
        $this->doCreate($hulpvrager);
    }

    public function update(Hulpvrager $hulpvrager)
    {
        $this->doUpdate($hulpvrager);
    }

    public function delete(Hulpvrager $hulpvrager)
    {
        $this->doDelete($hulpvrager);
    }
}
