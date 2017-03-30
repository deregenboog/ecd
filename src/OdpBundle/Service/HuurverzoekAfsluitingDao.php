<?php

namespace OdpBundle\Service;

use OdpBundle\Entity\Afsluiting;
use OdpBundle\Entity\HuurverzoekAfsluiting;

class HuurverzoekAfsluitingDao extends AbstractDao implements AfsluitingDaoInterface
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

    protected $class = HuurverzoekAfsluiting::class;

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
