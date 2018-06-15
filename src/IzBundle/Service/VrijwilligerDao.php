<?php

namespace IzBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\Query\Expr;
use IzBundle\Entity\IzVrijwilliger;

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
            'intakeMedewerker.voornaam',
            'hulpaanbodMedewerker.voornaam',
            'izVrijwilliger.afsluitDatum',
            'project.naam',
        ],
    ];

    protected $class = IzVrijwilliger::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $expr = new Expr();

        $builder = $this->repository->createQueryBuilder('izVrijwilliger')
            ->select('izVrijwilliger, vrijwilliger, hulpaanbod, project, intake, intakeMedewerker, hulpaanbodMedewerker')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('izVrijwilliger.intake', 'intake')
            ->leftJoin('intake.medewerker', 'intakeMedewerker')
            ->leftJoin('izVrijwilliger.izHulpaanbiedingen', 'hulpaanbod')
            ->leftJoin('hulpaanbod.project', 'project')
            ->leftJoin('hulpaanbod.medewerker', 'hulpaanbodMedewerker', 'WITH', $expr->andX(
                $expr->orX('hulpaanbod.einddatum IS NULL', 'hulpaanbod.einddatum > :now'),
                $expr->orX('hulpaanbod.koppelingEinddatum IS NULL', 'hulpaanbod.koppelingEinddatum > :now')
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
