<?php

namespace OdpBundle\Service;

use AppBundle\Service\AbstractDao;
use OdpBundle\Entity\Afsluiting;
use OdpBundle\Entity\HuurderAfsluiting;

class HuurderAfsluitingDao extends AbstractDao implements AfsluitingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'afsluiting.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'afsluiting.id',
            'afsluiting.naam',
            'afsluiting.actief',
            'afsluiting.tonen',
        ],
    ];

    protected $class = HuurderAfsluiting::class;

    protected $alias = 'afsluiting';

    public function create(Afsluiting $afsluiting)
    {
        $this->doCreate($afsluiting);
    }

    public function update(Afsluiting $afsluiting)
    {
        $this->doUpdate($afsluiting);
    }

    public function delete(Afsluiting $afsluiting)
    {
        $this->doDelete($afsluiting);
    }
}
