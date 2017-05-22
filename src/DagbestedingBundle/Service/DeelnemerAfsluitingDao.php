<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\DeelnemerAfsluiting;

class DeelnemerAfsluitingDao extends AbstractDao implements DeelnemerAfsluitingDaoInterface
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

    protected $class = DeelnemerAfsluiting::class;

    protected $alias = 'afsluiting';

    public function create(DeelnemerAfsluiting $afsluiting)
    {
        $this->doCreate($afsluiting);
    }

    public function update(DeelnemerAfsluiting $afsluiting)
    {
        $this->doUpdate($afsluiting);
    }

    public function delete(DeelnemerAfsluiting $afsluiting)
    {
        $this->doDelete($afsluiting);
    }
}
