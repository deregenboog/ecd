<?php

namespace IzBundle\Service;

use IzBundle\Entity\IzKlant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\Query\Expr;
use AppBundle\Entity\Klant;

class KlantDao extends AbstractDao implements KlantDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'klant.geboortedatum',
            'werkgebied.naam',
            'klant.laatsteZrm',
            'izIntakeMedewerker.voornaam',
            'hulpvraagMedewerker.voornaam',
            'izKlant.afsluitDatum',
            'project.naam',
        ],
    ];

    protected $class = IzKlant::class;

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $expr = new Expr();

        $builder = $this->repository->createQueryBuilder('izKlant')
            ->select('izKlant, klant, hulpvraag, project, izIntake, izIntakeMedewerker, hulpvraagMedewerker')
            ->innerJoin('izKlant.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->leftJoin('izKlant.izIntake', 'izIntake')
            ->leftJoin('izIntake.medewerker', 'izIntakeMedewerker')
            ->leftJoin('izKlant.izHulpvragen', 'hulpvraag')
            ->leftJoin('hulpvraag.project', 'project')
            ->leftJoin('hulpvraag.medewerker', 'hulpvraagMedewerker', 'WITH', $expr->andX(
                $expr->orX('hulpvraag.einddatum IS NULL', 'hulpvraag.einddatum > :now'),
                $expr->orX('hulpvraag.koppelingEinddatum IS NULL', 'hulpvraag.koppelingEinddatum > :now')
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

    /**
     * @param Klant $klant
     *
     * @return IzKlant
     */
    public function findOneByKlant(Klant $klant)
    {
        return $this->repository->findOneBy(['klant' => $klant]);
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
