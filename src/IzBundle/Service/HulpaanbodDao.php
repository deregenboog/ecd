<?php

namespace IzBundle\Service;

use IzBundle\Entity\Hulpvraag;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use IzBundle\Entity\Hulpaanbod;

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

    protected $class = Hulpaanbod::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('hulpaanbod')
            ->innerJoin('hulpaanbod.izVrijwilliger', 'izVrijwilliger')
            ->leftJoin('izVrijwilliger.intake', 'intake')
            ->innerJoin('hulpaanbod.project', 'project')
            ->innerJoin('hulpaanbod.medewerker', 'medewerker')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('vrijwilliger.werkgebied', 'werkgebied')
            ->where('hulpaanbod.hulpvraag IS NULL')
            ->andWhere('hulpaanbod.einddatum IS NULL')
            ->andWhere('izVrijwilliger.afsluiting IS NULL')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Hulpaanbod $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Hulpaanbod $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Hulpaanbod $entity)
    {
        $this->doDelete($entity);
    }
}
