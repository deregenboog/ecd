<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Trajectafsluiting;

class TrajectafsluitingDao extends AbstractDao implements TrajectafsluitingDaoInterface
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

    protected $class = Trajectafsluiting::class;

    protected $alias = 'afsluiting';

    public function create(Trajectafsluiting $afsluiting)
    {
        $this->doCreate($afsluiting);
    }

    public function update(Trajectafsluiting $afsluiting)
    {
        $this->doUpdate($afsluiting);
    }

    public function delete(Trajectafsluiting $afsluiting)
    {
        $this->doDelete($afsluiting);
    }
}
