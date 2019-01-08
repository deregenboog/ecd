<?php

namespace GaBundle\Service;

use AppBundle\Service\AbstractDao;
use GaBundle\Entity\ActiviteitAnnuleringsreden;

class ActiviteitAnnuleringsredenDao extends AbstractDao implements ActiviteitAnnuleringsredenDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'afsluitreden.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'afsluitreden.id',
            'afsluitreden.naam',
        ],
    ];

    protected $class = ActiviteitAnnuleringsreden::class;

    protected $alias = 'afsluitreden';

    public function create(ActiviteitAnnuleringsreden $entity)
    {
        $this->doCreate($entity);
    }

    public function update(ActiviteitAnnuleringsreden $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(ActiviteitAnnuleringsreden $entity)
    {
        $this->doDelete($entity);
    }
}
