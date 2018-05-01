<?php

namespace OdpBundle\Service;

use OdpBundle\Entity\Huurovereenkomst;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Filter\FilterInterface;

class HuurovereenkomstDao extends AbstractDao implements HuurovereenkomstDaoInterface
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

    public function countByWoningbouwcorporatie(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->addSelect('woningbouwcorporatie.naam AS groep')
            ->innerJoin("{$this->alias}.huuraanbod", 'huuraanbod')
            ->innerJoin('huuraanbod.verhuurder', 'verhuurder')
            ->leftJoin('verhuurder.woningbouwcorporatie', 'woningbouwcorporatie')
            ->andWhere("{$this->alias}.startdatum <= :end")
            ->andWhere("{$this->alias}.einddatum IS NULL OR {$this->alias}.einddatum >= :start")
            ->groupBy('groep')
        ;

        return $builder->getQuery()->getResult();
    }

    private function getCountBuilder(\DateTime $startdate, \DateTime $enddate)
    {
        return $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal")
            ->leftJoin("{$this->alias}.afsluiting", 'afsluiting')
            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
            ->setParameters(['start' => $startdate, 'end' => $enddate])
        ;
    }
}
