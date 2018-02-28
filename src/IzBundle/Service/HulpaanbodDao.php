<?php

namespace IzBundle\Service;

use IzBundle\Entity\IzHulpvraag;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use IzBundle\Entity\IzHulpaanbod;

class HulpaanbodDao extends AbstractDao implements HulpaanbodDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'hulpaanbod.startdatum',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'hulpaanbod.startdatum',
            'project.naam',
            'intake.intakeDatum',
            'vrijwilliger.id',
            'vrijwilliger.voornaam',
            'vrijwilliger.achternaam',
            'vrijwilliger.geboortedatum',
            'vrijwilliger.werkgebied',
            'vrijwilliger.vogAangevraagd',
            'vrijwilliger.vogAanwezig',
            'vrijwilliger.overeenkomstAanwezig',
            'werkgebied.naam',
            'medewerker.achternaam',
        ],
    ];

    protected $class = IzHulpaanbod::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('hulpaanbod')
            ->innerJoin('hulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->leftJoin('izVrijwilliger.izIntake', 'intake')
            ->innerJoin('hulpaanbod.izProject', 'project')
            ->innerJoin('hulpaanbod.medewerker', 'medewerker')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('vrijwilliger.werkgebied', 'werkgebied')
            ->where('hulpaanbod.izHulpvraag IS NULL')
            ->andWhere('hulpaanbod.einddatum IS NULL')
            ->andWhere('izVrijwilliger.izAfsluiting IS NULL')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(IzHulpaanbod $entity)
    {
        $this->doCreate($entity);
    }

    public function update(IzHulpaanbod $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(IzHulpaanbod $entity)
    {
        $this->doDelete($entity);
    }
}
