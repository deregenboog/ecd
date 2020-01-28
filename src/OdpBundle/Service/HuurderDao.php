<?php

namespace OdpBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Knp\Component\Pager\Pagination\PaginationInterface;
use OdpBundle\Entity\Huurder;

class HuurderDao extends AbstractDao implements HuurderDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'werkgebied.naam',
            'huurder.automatischeIncasso',
            'huurder.inschrijvingWoningnet',
            'huurder.waPolis',
            'huurder.aanmelddatum',
            'huurder.afsluitdatum',
            'huurder.wpi',
        ],
    ];

    protected $class = Huurder::class;

    protected $alias = 'huurder';
    protected $searchEntityName = 'klant';
    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {

        $builder = $this->repository->createQueryBuilder('huurder')
            ->innerJoin('huurder.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->leftJoin('huurder.afsluiting', 'afsluiting')
            ->andWhere('afsluiting.tonen IS NULL OR afsluiting.tonen = true')
        ;
//        $builder = $this->repository->createQueryBuilder($this->alias)
//            ->innerJoin($this->alias.'.klant', 'klant')
//        ;
        return parent::doFindAll($builder, $page, $filter);
    }
    public function create(Huurder $entity)
    {
        $this->doCreate($entity);
    }

    public function update(Huurder $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Huurder $entity)
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
            ->innerJoin("{$this->alias}.huurverzoeken", 'huurverzoek')
            ->innerJoin('huurverzoek.huurovereenkomst', 'huurovereenkomst')
            ->andWhere('huurovereenkomst.startdatum BETWEEN :start AND :end')
        ;

        return $builder->getQuery()->getResult();
    }

    public function countOntkoppeld(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->getCountBuilder($startdate, $enddate)
            ->innerJoin("{$this->alias}.huurverzoeken", 'huurverzoek')
            ->innerJoin('huurverzoek.huurovereenkomst', 'huurovereenkomst')
            ->andWhere('huurovereenkomst.einddatum BETWEEN :start AND :end')
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
