<?php

namespace ClipBundle\Service;

use AppBundle\Service\AbstractDao;
use ClipBundle\Entity\Leeftijdscategorie;

class LeeftijdscategorieDao extends AbstractDao implements LeeftijdscategorieDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'leeftijdscategorie.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'leeftijdscategorie.id',
            'leeftijdscategorie.naam',
            'leeftijdscategorie.actief',
        ],
    ];

    protected $class = Leeftijdscategorie::class;

    protected $alias = 'leeftijdscategorie';

    public function create(Leeftijdscategorie $leeftijdscategorie)
    {
        $this->doCreate($leeftijdscategorie);
    }

    public function update(Leeftijdscategorie $leeftijdscategorie)
    {
        $this->doUpdate($leeftijdscategorie);
    }

    public function delete(Leeftijdscategorie $leeftijdscategorie)
    {
        $this->doDelete($leeftijdscategorie);
    }
}
