<?php

namespace OdpBundle\Service;

use OdpBundle\Entity\Huurovereenkomst;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\FilterInterface;

class HuurovereenkomstDao extends AbstractDao
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'huurovereenkomst.id',
        'defaultSortDirection' => 'asc',
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
            'huurder.automatischeIncasso',
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
            ->innerJoin('huurverzoek.huurder', 'huurder')
            ->innerJoin('huuraanbod.verhuurder', 'verhuurder')
            ->innerJoin('huurder.klant', 'klant')
            ->innerJoin('verhuurder.klant', 'verhuurderKlant')
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

    public function countByWoningbouwcorporatie(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, woningbouwcorporatie.naam AS groep")
            ->innerJoin("{$this->alias}.huuraanbod", 'huuraanbod')
            ->innerJoin('huuraanbod.verhuurder', 'verhuurder')
            ->leftJoin('verhuurder.woningbouwcorporatie', 'woningbouwcorporatie')
            ->leftJoin("{$this->alias}.afsluiting", 'afsluiting')
            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
            ->groupBy('groep')
        ;

        $this->applyFilter($builder, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    protected function applyFilter(QueryBuilder $builder, \DateTime $startdate, \DateTime $enddate)
    {
        $builder
            ->andWhere("{$this->alias}.startdatum < :end")
            ->andWhere("{$this->alias}.einddatum IS NULL OR {$this->alias}.einddatum > :start")
            ->setParameters([
                'start' => $startdate,
                'end' => $enddate,
            ])
        ;
    }
}
