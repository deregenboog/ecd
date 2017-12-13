<?php

namespace IzBundle\Service;

use IzBundle\Entity\Doelgroep;
use AppBundle\Service\AbstractDao;

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
