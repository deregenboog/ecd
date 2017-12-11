<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Locatie;

class LocatieDao extends AbstractDao implements LocatieDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'locatie.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'locatie.id',
            'locatie.naam',
            'locatie.actief',
        ],
    ];

    protected $class = Locatie::class;

    protected $alias = 'locatie';

    public function create(Locatie $locatie)
    {
        $this->doCreate($locatie);
    }

    public function update(Locatie $locatie)
    {
        $this->doUpdate($locatie);
    }

    public function delete(Locatie $locatie)
    {
        $this->doDelete($locatie);
    }
}
