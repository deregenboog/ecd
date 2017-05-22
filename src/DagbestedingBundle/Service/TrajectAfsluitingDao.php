<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\TrajectAfsluiting;

class TrajectAfsluitingDao extends AbstractDao implements TrajectAfsluitingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'afsluiting.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'afsluiting.id',
            'afsluiting.naam',
            'afsluiting.actief',
        ],
    ];

    protected $class = TrajectAfsluiting::class;

    protected $alias = 'afsluiting';

    public function create(TrajectAfsluiting $afsluiting)
    {
        $this->doCreate($afsluiting);
    }

    public function update(TrajectAfsluiting $afsluiting)
    {
        $this->doUpdate($afsluiting);
    }

    public function delete(TrajectAfsluiting $afsluiting)
    {
        $this->doDelete($afsluiting);
    }
}
