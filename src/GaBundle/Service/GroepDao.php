<?php

namespace GaBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use GaBundle\Entity\GaGroep;

class GroepDao extends AbstractDao implements GroepDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'groep.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'groep.naam',
            'groep.startdatum',
            'groep.einddatum',
            'werkgebied.naam',
        ],
    ];

    protected $class = GaGroep::class;

    protected $alias = 'groep';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias);

        return $this->doFindAll($builder, $page, $filter);
    }

    public function create(GaGroep $entity)
    {
        $this->doCreate($entity);
    }

    public function update(GaGroep $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(GaGroep $entity)
    {
        $this->doDelete($entity);
    }
}
