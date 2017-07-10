<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Locatie;

class LocatieDao extends AbstractDao implements LocatieDaoInterface
{
    protected $paginationOptions = [
//         'defaultSortFieldName' => 'klant.achternaam',
//         'defaultSortDirection' => 'asc',
//         'sortFieldWhitelist' => [
//             'klant.id',
//             'klant.achternaam',
//             'klant.werkgebied',
//         ],
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
