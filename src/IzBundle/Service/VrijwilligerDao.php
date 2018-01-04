<?php

namespace IzBundle\Service;

use IzBundle\Entity\IzVrijwilliger;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\Query\Expr;

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
            'izIntakeMedewerker.voornaam',
            'izHulpaanbodMedewerker.voornaam',
            'izVrijwilliger.afsluitDatum',
            'izProject.naam',
        ],
    ];

    protected $class = IzVrijwilliger::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $expr = new Expr();

        $builder = $this->repository->createQueryBuilder('izVrijwilliger')
            ->select('izVrijwilliger, vrijwilliger, izHulpaanbod, izProject, izIntake, izIntakeMedewerker, izHulpaanbodMedewerker')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('izVrijwilliger.izIntake', 'izIntake')
            ->leftJoin('izIntake.medewerker', 'izIntakeMedewerker')
            ->leftJoin('izVrijwilliger.izHulpaanbiedingen', 'izHulpaanbod')
            ->leftJoin('izHulpaanbod.izProject', 'izProject')
            ->leftJoin('izHulpaanbod.medewerker', 'izHulpaanbodMedewerker', 'WITH', $expr->andX(
                $expr->orX('izHulpaanbod.einddatum IS NULL', 'izHulpaanbod.einddatum > :now'),
                $expr->orX('izHulpaanbod.koppelingEinddatum IS NULL', 'izHulpaanbod.koppelingEinddatum > :now')
            ))
            ->setParameter('now', new \DateTime())
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
