<?php

namespace OekraineBundle\Service;

use AppBundle\Service\AbstractDao;
use OekraineBundle\Entity\Training;

class TrainingDao extends AbstractDao implements TrainingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'training.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'training.id',
            'training.naam',
            'training.actief',
        ],
    ];

    protected $class = Training::class;

    protected $alias = 'training';

    public function create(Training $vwtraining)
    {
        $this->doCreate($vwtraining);
    }

    public function update(Training $vwtraining)
    {
        $this->doUpdate($vwtraining);
    }

    public function delete(Training $vwtraining)
    {
        $this->doDelete($vwtraining);
    }
}
