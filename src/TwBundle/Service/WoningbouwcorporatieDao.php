<?php

namespace TwBundle\Service;

use AppBundle\Service\AbstractDao;
use TwBundle\Entity\Pandeigenaar;

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

    protected $class = Pandeigenaar::class;

    protected $alias = 'woningbouwcorporatie';

    public function create(Pandeigenaar $woningbouwcorporatie)
    {
        $this->doCreate($woningbouwcorporatie);
    }

    public function update(Pandeigenaar $woningbouwcorporatie)
    {
        $this->doUpdate($woningbouwcorporatie);
    }

    public function delete(Pandeigenaar $woningbouwcorporatie)
    {
        $this->doDelete($woningbouwcorporatie);
    }
}
