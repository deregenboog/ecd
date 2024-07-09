<?php

namespace TwBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Model\UsesKlantTrait;
use AppBundle\Service\AbstractDao;
use TwBundle\Entity\Verhuurder;

class VerhuurderDao extends AbstractDao implements VerhuurderDaoInterface
{
    use UsesKlantTrait;

    protected $paginationOptions = [
        'defaultSortFieldName' => 'appKlant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'appKlant.id',
            'appKlant.achternaam',
            'werkgebied.naam',
            'verhuurder.aanmelddatum',
            'verhuurder.afsluitdatum',
            'verhuurder.wpi',
            'verhuurder.ksgw',
            'ambulantOndersteuner.achternaam',
            'project.naam',
        ],
//        'wrap-queries'=>true,
    ];

    protected $class = Verhuurder::class;

    protected $alias = 'verhuurder';

    protected $searchEntityName = 'appKlant';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('verhuurder')
            ->innerJoin('verhuurder.appKlant', 'appKlant')
            ->leftJoin('verhuurder.ambulantOndersteuner', 'ambulantOndersteuner')
            ->leftJoin('appKlant.werkgebied', 'werkgebied')
            ->leftJoin('verhuurder.afsluiting', 'afsluiting')
            ->leftJoin('verhuurder.project', 'project')
            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    public function create(Verhuurder $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Verhuurder $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Verhuurder $entity)
    {
        $this->doDelete($entity);
    }

    public function countAangemeld(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->andWhere("{$this->alias}.aanmelddatum BETWEEN :start AND :end")
        ;

        return $builder->getQuery()->getResult();
    }

    public function countGekoppeld(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->innerJoin("{$this->alias}.huuraanbiedingen", 'huuraanbod')
            ->innerJoin('huuraanbod.huurovereenkomst', 'huurovereenkomst')
            ->andWhere('huurovereenkomst.startdatum BETWEEN :start AND :end')
            ->andWhere('huurovereenkomst.isReservering = 0')
        ;

        return $builder->getQuery()->getResult();
    }

    public function countOntkoppeld(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->innerJoin("{$this->alias}.huuraanbiedingen", 'huuraanbod')
            ->innerJoin('huuraanbod.huurovereenkomst', 'huurovereenkomst')
            ->andWhere('huurovereenkomst.einddatum BETWEEN :start AND :end')
            ->andWhere('huurovereenkomst.isReservering = 0')
        ;

        return $builder->getQuery()->getResult();
    }

    public function countAfgesloten(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->andWhere("{$this->alias}.afsluitdatum BETWEEN :start AND :end")
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
