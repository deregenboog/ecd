<?php

namespace IzBundle\Service;

use IzBundle\Entity\Hulpvraagsoort;
use AppBundle\Service\AbstractDao;

class HulpvraagsoortDao extends AbstractDao implements HulpvraagsoortDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'hulpvraagsoort.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'hulpvraagsoort.naam',
            'hulpvraagsoort.toelichting',
            'hulpvraagsoort.actief',
        ],
    ];

    protected $class = Hulpvraagsoort::class;

    protected $alias = 'hulpvraagsoort';

    public function create(Hulpvraagsoort $hulpvraagsoort)
    {
        $this->doCreate($hulpvraagsoort);
    }

    public function update(Hulpvraagsoort $hulpvraagsoort)
    {
        $this->doUpdate($hulpvraagsoort);
    }

    public function delete(Hulpvraagsoort $hulpvraagsoort)
    {
        $this->doDelete($hulpvraagsoort);
    }
}
