<?php

namespace OdpBundle\Service;

use OdpBundle\Entity\Woningbouwcorporatie;

class WoningbouwcorporatieDao extends AbstractDao implements WoningbouwcorporatieDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'woningbouwcorporatie.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'woningbouwcorporatie.id',
            'woningbouwcorporatie.naam',
            'woningbouwcorporatie.actief',
        ],
    ];

    protected $class = Woningbouwcorporatie::class;

    protected $alias = 'woningbouwcorporatie';

    public function create(Woningbouwcorporatie $woningbouwcorporatie)
    {
        $this->doCreate($woningbouwcorporatie);
    }

    public function update(Woningbouwcorporatie $woningbouwcorporatie)
    {
        $this->doUpdate($woningbouwcorporatie);
    }

    public function delete(Woningbouwcorporatie $woningbouwcorporatie)
    {
        $this->doDelete($woningbouwcorporatie);
    }
}
