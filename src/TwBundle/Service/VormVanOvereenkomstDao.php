<?php

namespace TwBundle\Service;

use AppBundle\Service\AbstractDao;
use TwBundle\Entity\VormVanOvereenkomst;

class VormVanOvereenkomstDao extends AbstractDao implements VormVanOvereenkomstDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'vormvanovereenkomst.label',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'vormvanovereenkomst.label',
            'project.startdate',
        ],
    ];

    protected $class = VormVanOvereenkomst::class;

    protected $alias = 'vormvanovereenkomst';

    public function create(VormVanOvereenkomst $vormVanOvereenkomst)
    {
        $this->doCreate($vormVanOvereenkomst);
    }

    public function update(VormVanOvereenkomst $vormVanOvereenkomst)
    {
        $this->doUpdate($vormVanOvereenkomst);
    }

    public function delete(VormVanOvereenkomst $vormVanOvereenkomst)
    {
        $this->doDelete($vormVanOvereenkomst);
    }
}
