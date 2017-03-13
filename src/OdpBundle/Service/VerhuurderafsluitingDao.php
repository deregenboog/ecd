<?php

namespace OdpBundle\Service;

use OdpBundle\Entity\Verhuurderafsluiting;
use AppBundle\Service\AbstractDao;

class VerhuurderafsluitingDao extends AbstractDao implements VerhuurderafsluitingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'verhuurderafsluiting.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'verhuurderafsluiting.id',
            'verhuurderafsluiting.naam',
            'verhuurderafsluiting.actief',
        ],
    ];

    protected $class = Verhuurderafsluiting::class;

    protected $alias = 'verhuurderafsluiting';

    public function create(Verhuurderafsluiting $verhuurderafsluiting)
    {
        $this->doCreate($verhuurderafsluiting);
    }

    public function update(Verhuurderafsluiting $verhuurderafsluiting)
    {
        $this->doUpdate($verhuurderafsluiting);
    }

    public function delete(Verhuurderafsluiting $verhuurderafsluiting)
    {
        $this->doDelete($verhuurderafsluiting);
    }
}
