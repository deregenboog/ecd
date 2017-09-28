<?php

namespace ClipBundle\Service;

use AppBundle\Service\AbstractDao;
use ClipBundle\Entity\Viacategorie;

class ViacategorieDao extends AbstractDao implements ViacategorieDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'viacategorie.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'viacategorie.id',
            'viacategorie.naam',
            'viacategorie.actief',
        ],
    ];

    protected $class = Viacategorie::class;

    protected $alias = 'viacategorie';

    public function create(Viacategorie $viacategorie)
    {
        $this->doCreate($viacategorie);
    }

    public function update(Viacategorie $viacategorie)
    {
        $this->doUpdate($viacategorie);
    }

    public function delete(Viacategorie $viacategorie)
    {
        $this->doDelete($viacategorie);
    }
}
