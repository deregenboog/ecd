<?php

namespace IzBundle\Service;

use IzBundle\Entity\IzVrijwilliger;
use AppBundle\Filter\FilterInterface;

class VrijwilligerDao extends AbstractDao implements VrijwilligerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'vrijwilliger.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'vrijwilliger.id',
            'vrijwilliger.achternaam',
            'vrijwilliger.geboortedatum',
            'vrijwilliger.werkgebied',
            'medewerker.voornaam',
            'izProject.naam',
        ],
    ];

    protected $class = IzVrijwilliger::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('izKlant')
            ->innerJoin('izKlant.vrijwilliger', 'vrijwilliger')
            ->leftJoin('izKlant.izHulpaanbiedingen', 'izHulpaanbod')
            ->leftJoin('izHulpaanbod.izProject', 'izProject')
            ->leftJoin('izHulpaanbod.medewerker', 'medewerker')
            ->where('vrijwilliger.disabled = false')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(IzVrijwilliger $vrijwilliger)
    {
        $this->doCreate($vrijwilliger);
    }

    public function update(IzVrijwilliger $vrijwilliger)
    {
        $this->doUpdate($vrijwilliger);
    }

    public function delete(IzVrijwilliger $vrijwilliger)
    {
        $this->doDelete($vrijwilliger);
    }
}
