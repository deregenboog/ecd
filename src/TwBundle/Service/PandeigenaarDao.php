<?php

namespace TwBundle\Service;

use AppBundle\Service\AbstractDao;
use TwBundle\Entity\Pandeigenaar;

class PandeigenaarDao extends AbstractDao implements PandeigenaarDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'pandeigenaar.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'pandeigenaar.id',
            'pandeigenaar.naam',
            'pandeigenaar.pandeigenaarType.naam',
            'pandeigenaar.actief',
        ],
    ];

    protected $class = Pandeigenaar::class;

    protected $alias = 'pandeigenaar';

    public function create(Pandeigenaar $pandeigenaar)
    {
        $this->doCreate($pandeigenaar);
    }

    public function update(Pandeigenaar $pandeigenaar)
    {
        $this->doUpdate($pandeigenaar);
    }

    public function delete(Pandeigenaar $pandeigenaar)
    {
        $this->doDelete($pandeigenaar);
    }
}
