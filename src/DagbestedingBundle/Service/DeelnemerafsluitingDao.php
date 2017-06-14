<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Deelnemerafsluiting;

class DeelnemerafsluitingDao extends AbstractDao implements DeelnemerafsluitingDaoInterface
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

    protected $class = Deelnemerafsluiting::class;

    protected $alias = 'afsluiting';

    public function create(Deelnemerafsluiting $afsluiting)
    {
        $this->doCreate($afsluiting);
    }

    public function update(Deelnemerafsluiting $afsluiting)
    {
        $this->doUpdate($afsluiting);
    }

    public function delete(Deelnemerafsluiting $afsluiting)
    {
        $this->doDelete($afsluiting);
    }
}
