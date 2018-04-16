<?php

namespace IzBundle\Service;

use IzBundle\Entity\Intervisiegroep;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;

class IntervisiegroepDao extends AbstractDao implements IntervisiegroepDaoInterface
{
    protected $paginationOptions = [
            'defaultSortFieldName' => 'intervisiegroep.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'intervisiegroep.id',
            'intervisiegroep.naam',
            'intervisiegroep.startdatum',
            'intervisiegroep.einddatum',
            'medewerker.voornaam',
        ],
    ];

    protected $class = Intervisiegroep::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('intervisiegroep')
            ->leftJoin('intervisiegroep.medewerker', 'medewerker')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Intervisiegroep $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Intervisiegroep $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Intervisiegroep $entity)
    {
        $this->doDelete($entity);
    }
}
