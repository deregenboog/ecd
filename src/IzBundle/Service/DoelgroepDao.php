<?php

namespace IzBundle\Service;

use AppBundle\Service\AbstractDao;
use IzBundle\Entity\Doelgroep;

class DoelgroepDao extends AbstractDao implements DoelgroepDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'doelgroep.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'doelgroep.naam',
            'doelgroep.actief',
        ],
    ];

    protected $class = Doelgroep::class;

    protected $alias = 'doelgroep';

    public function create(Doelgroep $doelgroep)
    {
        $this->doCreate($doelgroep);
    }

    public function update(Doelgroep $doelgroep)
    {
        $this->doUpdate($doelgroep);
    }

    public function delete(Doelgroep $doelgroep)
    {
        $this->doDelete($doelgroep);
    }
}
