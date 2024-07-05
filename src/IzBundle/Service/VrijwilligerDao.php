<?php

namespace IzBundle\Service;

use AppBundle\Entity\Vrijwilliger;
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
            'werkgebied.naam',
            'aanmeldingMedewerker.voornaam',
            'intakeMedewerker.voornaam',
            'hulpaanbodMedewerker.voornaam',
            'izVrijwilliger.afsluitDatum',
            'project.naam',
            'hulpvraagsoort.naam',
            'doelgroep.naam',
        ],
    ];

    protected $class = IzVrijwilliger::class;

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $expr = new Expr();

        $builder = $this->repository->createQueryBuilder('izVrijwilliger')
//            ->select('izVrijwilliger, vrijwilliger, hulpaanbod, project, intake, intakeMedewerker, hulpaanbodMedewerker')
            ->select('izVrijwilliger, vrijwilliger')
            ->innerJoin('izVrijwilliger.vrijwilliger', 'vrijwilliger')
            ->leftJoin('vrijwilliger.werkgebied', 'werkgebied')
            ->leftJoin('izVrijwilliger.medewerker', 'aanmeldingMedewerker')
            ->leftJoin('izVrijwilliger.intake', 'intake')
            ->leftJoin('intake.medewerker', 'intakeMedewerker')
            ->leftJoin('izVrijwilliger.hulpaanbiedingen', 'hulpaanbod')
            ->leftJoin('hulpaanbod.project', 'project')
            ->leftJoin('hulpaanbod.medewerker', 'hulpaanbodMedewerker', 'WITH', $expr->andX(
                $expr->orX('hulpaanbod.einddatum IS NULL', 'hulpaanbod.einddatum > :now'),
                $expr->orX('hulpaanbod.koppelingEinddatum IS NULL', 'hulpaanbod.koppelingEinddatum > :now')
            ))
            ->leftJoin('hulpaanbod.hulpvraagsoorten', 'hulpvraagsoort')
            ->leftJoin('hulpaanbod.doelgroepen', 'doelgroep')
            ->setParameter('now', new \DateTime())
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        //        $sql = $builder->getQuery()->getSQL();
        $r = $builder->getQuery()->getResult();

        //        $i = count($r);
        return $r;
    }

    /**
     * @return IzVrijwilliger
     */
    public function findOneByVrijwilliger(Vrijwilliger $vrijwilliger)
    {
        $this->entityManager->getFilters()->disable('foutieve_invoer');
        $vw = $this->repository->findOneBy(['vrijwilliger' => $vrijwilliger]);
        $this->entityManager->getFilters()->enable('foutieve_invoer');

        return $vw;
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
