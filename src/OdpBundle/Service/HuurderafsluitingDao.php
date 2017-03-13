<?php

namespace OdpBundle\Service;

use OdpBundle\Entity\Huurderafsluiting;
use AppBundle\Service\AbstractDao;

class HuurderafsluitingDao extends AbstractDao implements HuurderafsluitingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'huurderafsluiting.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'huurderafsluiting.id',
            'huurderafsluiting.naam',
            'huurderafsluiting.actief',
        ],
    ];

    protected $class = Huurderafsluiting::class;

    protected $alias = 'huurderafsluiting';

    public function create(Huurderafsluiting $huurderafsluiting)
    {
        $this->doCreate($huurderafsluiting);
    }

    public function update(Huurderafsluiting $huurderafsluiting)
    {
        $this->doUpdate($huurderafsluiting);
    }

    public function delete(Huurderafsluiting $huurderafsluiting)
    {
        $this->doDelete($huurderafsluiting);
    }
}
