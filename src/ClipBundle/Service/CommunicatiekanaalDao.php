<?php

namespace ClipBundle\Service;

use AppBundle\Service\AbstractDao;
use ClipBundle\Entity\Communicatiekanaal;

class CommunicatiekanaalDao extends AbstractDao implements CommunicatiekanaalDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'communicatiekanaal.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'communicatiekanaal.id',
            'communicatiekanaal.naam',
            'communicatiekanaal.actief',
        ],
    ];

    protected $class = Communicatiekanaal::class;

    protected $alias = 'communicatiekanaal';

    public function create(Communicatiekanaal $communicatiekanaal)
    {
        $this->doCreate($communicatiekanaal);
    }

    public function update(Communicatiekanaal $communicatiekanaal)
    {
        $this->doUpdate($communicatiekanaal);
    }

    public function delete(Communicatiekanaal $communicatiekanaal)
    {
        $this->doDelete($communicatiekanaal);
    }
}
