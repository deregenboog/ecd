<?php

namespace IzBundle\Service;

use IzBundle\Entity\IzKlant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\Query\Expr;

class KlantDao extends AbstractDao implements KlantDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'klant.geboortedatum',
            'klant.werkgebied',
            'klant.laatsteZrm',
            'izIntakeMedewerker.voornaam',
            'izHulpvraagMedewerker.voornaam',
            'izKlant.afsluitDatum',
            'izProject.naam',
        ],
    ];

    protected $class = IzKlant::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $expr = new Expr();

        $builder = $this->repository->createQueryBuilder('izKlant')
            ->select('izKlant, klant, izHulpvraag, izProject, izIntake, izIntakeMedewerker, izHulpvraagMedewerker')
            ->innerJoin('izKlant.klant', 'klant')
            ->leftJoin('izKlant.izIntake', 'izIntake')
            ->leftJoin('izIntake.medewerker', 'izIntakeMedewerker')
            ->leftJoin('izKlant.izHulpvragen', 'izHulpvraag')
            ->leftJoin('izHulpvraag.izProject', 'izProject')
            ->leftJoin('izHulpvraag.medewerker', 'izHulpvraagMedewerker', 'WITH', $expr->andX(
                $expr->orX('izHulpvraag.einddatum IS NULL', 'izHulpvraag.einddatum > :now'),
                $expr->orX('izHulpvraag.koppelingEinddatum IS NULL', 'izHulpvraag.koppelingEinddatum > :now')
            ))
            ->where('klant.disabled = false')
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

    public function create(IzKlant $klant)
    {
        $this->doCreate($klant);
    }

    public function update(IzKlant $klant)
    {
        $this->doUpdate($klant);
    }

    public function delete(IzKlant $klant)
    {
        $this->doDelete($klant);
    }
}
