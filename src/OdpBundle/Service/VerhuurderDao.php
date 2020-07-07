<?php

namespace OdpBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Model\UsesKlantTrait;
use AppBundle\Service\AbstractDao;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OdpBundle\Entity\Verhuurder;

class VerhuurderDao extends AbstractDao implements VerhuurderDaoInterface
{
    use UsesKlantTrait;

    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'werkgebied.naam',
            'verhuurder.aanmelddatum',
            'verhuurder.afsluitdatum',
            'verhuurder.wpi',
            'verhuurder.ksgw',
            'ambulantOndersteuner.achternaam'
        ],
//        'wrap-queries'=>true,
    ];

    protected $class = Verhuurder::class;

    protected $alias = 'verhuurder';

    protected $searchEntityName = 'klant';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('verhuurder')
            ->innerJoin('verhuurder.klant', 'klant')
            ->leftJoin('verhuurder.ambulantOndersteuner','ambulantOndersteuner')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->leftJoin('verhuurder.afsluiting', 'afsluiting')
            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
        ;
//        $builder = $this->repository->createQueryBuilder($this->alias)
//            ->innerJoin($this->alias.'.klant', 'klant')
//        ;
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

//    private function getCountBuilder(\DateTime $startdate, \DateTime $enddate)
//    {
//        return $this->repository->createQueryBuilder($this->alias)
//            ->select("COUNT({$this->alias}.id) AS aantal")
//            ->leftJoin("{$this->alias}.afsluiting", 'afsluiting')
//            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
//            ->setParameters(['start' => $startdate, 'end' => $enddate])
//        ;
//    }
}
