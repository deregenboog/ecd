<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\QueryBuilder;
use TwBundle\Entity\Huurovereenkomst;

class HuurovereenkomstDao extends AbstractDao implements HuurovereenkomstDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'huurovereenkomst.id',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'huurovereenkomst.id',
            'klant.achternaam',
            'verhuurderKlant.achternaam',
            'verhuurderKlant.plaats',
            'medewerker.voornaam',
            'huurovereenkomst.startdatum',
            'huurovereenkomst.opzegdatum',
            'huurovereenkomst.einddatum',
            'huurovereenkomst.vorm',
            'huurovereenkomst.afsluitdatum',
            'huurovereenkomst.isReservering',
            'huurovereenkomst.opzegbriefVerstuurd',
            'klant.automatischeIncasso',
            'project.naam'
        ],
    ];

    protected $class = Huurovereenkomst::class;

    protected $alias = 'huurovereenkomst';

    protected function doFindAll(QueryBuilder $builder, $page = 1, FilterInterface $filter = null)
    {
        $builder
            ->innerJoin('huurovereenkomst.huurverzoek', 'huurverzoek')
            ->innerJoin('huurovereenkomst.huuraanbod', 'huuraanbod')
            ->innerJoin('huurovereenkomst.medewerker', 'medewerker')
            ->innerJoin('huurverzoek.klant', 'klant')
            ->innerJoin('huuraanbod.verhuurder', 'verhuurder')
            ->leftJoin('huuraanbod.project','project')

            ->innerJoin('klant.appKlant', 'appKlant')
            ->innerJoin('verhuurder.appKlant', 'verhuurderKlant')
            ->leftJoin('huurovereenkomst.afsluiting', 'afsluiting')
            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    public function create(Huurovereenkomst $huurovereenkomst)
    {
        $this->doCreate($huurovereenkomst);
    }

    public function update(Huurovereenkomst $huurovereenkomst)
    {
        $this->doUpdate($huurovereenkomst);
    }

    public function delete(Huurovereenkomst $huurovereenkomst)
    {
        $this->doDelete($huurovereenkomst);
    }

    public function countByProject(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)

            ->addSelect("project.naam AS groep")
            ->innerJoin('huurovereenkomst.huuraanbod', 'huuraanbod')
            ->innerJoin('huuraanbod.project', 'project')
            ->andWhere("{$this->alias}.startdatum <= :end")
            ->andWhere("{$this->alias}.einddatum IS NULL OR {$this->alias}.einddatum >= :start")
            ->groupBy('project.id')
        ;
//        $sql = $builder->getQuery()->getSQL();

        return $builder->getQuery()->getResult();
    }

    public function countByVormvanovereenkomst(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)

            ->addSelect("vormvanovereenkomst.label AS groep")
            ->innerJoin('huurovereenkomst.huuraanbod', 'huuraanbod')
            ->leftJoin('huuraanbod.vormvanovereenkomst', 'vormvanovereenkomst')
            ->andWhere("vormvanovereenkomst.startdate <= :end")
            ->andWhere("vormvanovereenkomst.enddate IS NULL OR vormvanovereenkomst.enddate >= :start")
            ->andWhere("{$this->alias}.startdatum <= :end")
            ->andWhere("{$this->alias}.einddatum IS NULL OR {$this->alias}.einddatum >= :start")
            ->groupBy('vormvanovereenkomst.id')
        ;

        return $builder->getQuery()->getResult();
    }

    public function countByVorm(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->addSelect("{$this->alias}.vorm AS groep")
            ->andWhere("{$this->alias}.startdatum <= :end")
            ->andWhere("{$this->alias}.einddatum IS NULL OR {$this->alias}.einddatum >= :start")
            ->groupBy('groep')
        ;

        return $builder->getQuery()->getResult();
    }


    public function countByPandeigenaar(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->addSelect('pandeigenaar.naam AS groep')
            ->innerJoin("{$this->alias}.huuraanbod", 'huuraanbod')
            ->innerJoin('huuraanbod.verhuurder', 'verhuurder')
            ->leftJoin('verhuurder.pandeigenaar', 'pandeigenaar')
            ->andWhere("{$this->alias}.startdatum <= :end")
            ->andWhere("{$this->alias}.einddatum IS NULL OR {$this->alias}.einddatum >= :start")
            ->groupBy('groep')
        ;

        return $builder->getQuery()->getResult();
    }

    public function countByAfsluitreden(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->addSelect('afsluitreden.naam AS groep')
            ->innerJoin("{$this->alias}.afsluiting", 'afsluitreden')
            ->andWhere("{$this->alias}.startdatum <= :end")
            ->andWhere("{$this->alias}.einddatum >= :start")
            ->groupBy('groep')
        ;

        return $builder->getQuery()->getResult();
    }


    public function countByStadsdeel(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->addSelect('werkgebied.naam AS groep')
            ->innerJoin("{$this->alias}.huuraanbod", 'huuraanbod')
            ->innerJoin("{$this->alias}.huurverzoek", 'huurverzoek')
            ->innerJoin('huuraanbod.verhuurder', 'verhuurder')
            ->innerJoin('huurverzoek.klant', 'klant')
            ->innerJoin('klant.appKlant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->andWhere("{$this->alias}.startdatum <= :end")
            ->andWhere("{$this->alias}.einddatum IS NULL OR {$this->alias}.einddatum >= :start")
//            ->andWhere('werkgebied.zichtbaar >= 0')
            ->groupBy('werkgebied')
        ;

        return $builder->getQuery()->getResult();
    }
    public function countByPlaats(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->addSelect('klant.plaats AS groep')
            ->innerJoin("{$this->alias}.huuraanbod", 'huuraanbod')
            ->innerJoin("{$this->alias}.huurverzoek", 'huurverzoek')
            ->innerJoin('huuraanbod.verhuurder', 'verhuurder')
            ->innerJoin('huurverzoek.klant', 'klant')
            ->innerJoin('klant.appKlant', 'appKlant')
//            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->andWhere("{$this->alias}.startdatum <= :end")
            ->andWhere("{$this->alias}.einddatum IS NULL OR {$this->alias}.einddatum >= :start")
//            ->andWhere('werkgebied.zichtbaar >= 0')
            ->groupBy('klant.plaats')
        ;

        return $builder->getQuery()->getResult();
    }

    private function getCountBuilder(\DateTime $startdate, \DateTime $enddate)
    {
        return $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal")
            ->leftJoin("{$this->alias}.afsluiting", 'afsluiting')
            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
            ->andWhere('huurovereenkomst.isReservering = 0')
            ->setParameters(['start' => $startdate, 'end' => $enddate])
        ;
    }
}
