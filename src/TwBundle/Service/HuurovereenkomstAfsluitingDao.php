<?php

namespace TwBundle\Service;

use AppBundle\Service\AbstractDao;
use TwBundle\Entity\Afsluiting;
use TwBundle\Entity\HuurovereenkomstAfsluiting;

class HuurovereenkomstAfsluitingDao extends AbstractDao implements HuurovereenkomstAfsluitingDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'afsluiting.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'afsluiting.id',
            'afsluiting.naam',
            'afsluiting.actief',
            'afsluiting.tonen',
        ],
    ];

    protected $class = HuurovereenkomstAfsluiting::class;

    protected $alias = 'afsluiting';

    public function create(Afsluiting $afsluiting)
    {
        $this->doCreate($afsluiting);
    }

    public function update(Afsluiting $afsluiting)
    {
        $this->doUpdate($afsluiting);
    }

    public function delete(Afsluiting $afsluiting)
    {
        $this->doDelete($afsluiting);
    }

    public function countByProject(\DateTime $start, \DateTime $end)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal", 'project.naam AS projectnaam', 'afsluiting.naam AS afsluitreden')
            ->innerJoin("{$this->alias}.huurovereenkomsten", 'huurovereenkomst')
            ->innerJoin('huurovereenkomst.huuraanbod', 'huuraanbod')
            ->innerJoin('huuraanbod.project', 'project')
            ->where('huurovereenkomst.afsluitdatum BETWEEN :start AND :end')
            ->andWhere('huurovereenkomst.isReservering = 0')
            ->groupBy('project.id')
            ->setParameters(['start' => $start, 'end' => $end])
        ;

        return $builder->getQuery()->getResult();
    }

    public function countByKlant(\DateTime $start, \DateTime $end)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal",  'afsluiting.naam AS afsluitreden')
            ->innerJoin("{$this->alias}.huurovereenkomsten", 'huurovereenkomst')
            ->innerJoin('huurovereenkomst.huuraanbod', 'huuraanbod')
            ->where('huurovereenkomst.afsluitdatum BETWEEN :start AND :end')
            ->andWhere('huurovereenkomst.isReservering = 0')
            ->groupBy('afsluiting.id')
            ->setParameters(['start' => $start, 'end' => $end])
        ;

        return $builder->getQuery()->getResult();
    }
}
