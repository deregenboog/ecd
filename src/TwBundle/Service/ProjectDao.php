<?php

namespace TwBundle\Service;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\Query\Expr\Join;
use TwBundle\Entity\Project;

class ProjectDao extends AbstractDao implements ProjectDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'project.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'project.naam',
            'project.startdatum',
            'project.einddatum',
            'project.heeftKoppelingen',
            'project.prestatieStrategy',
        ],
    ];

    protected $class = Project::class;

    protected $alias = 'project';

    public function create(Project $project)
    {
        $this->doCreate($project);
    }

    public function update(Project $project)
    {
        $this->doUpdate($project);
    }

    public function delete(Project $project)
    {
        $this->doDelete($project);
    }

    public function countKoppelingenPerProject(\DateTime $startdate, \DateTime $enddate)
    {
        /**
         * SELECT p.naam, COUNT(k.id) AS actief
         * , COUNT(k2.id) AS gestart
         * FROM tw_projecten AS p
         * INNER JOIN tw_huuraanbiedingen AS a ON a.project_id = p.id
         * LEFT JOIN tw_huurovereenkomsten AS k ON k.huuraanbod_id = a.id AND k.startdatum <= '2020-07-02' AND (k.einddatum >= '2020-01-01' OR k.einddatum IS NULL)
         * LEFT JOIN tw_huurovereenkomsten AS k2 ON k2.huuraanbod_id = a.id AND (k2.startdatum >= '2020-01-01' AND k2.startdatum <= '2020-01-01')
         * LEFT JOIN tw_afsluitingen AS af1 ON af1.id = k.afsluiting_id AND af1.discr IN ('huurovereenkomst')
         * LEFT JOIN tw_afsluitingen AS af2 ON af2.id = k2.afsluiting_id AND af2.discr IN ('huurovereenkomst')
         * WHERE
         * ( (af1.tonen IS NULL OR af1.tonen = 1)
         * AND
         * (af2.tonen IS NULL OR af2.tonen = 1)
         * ) AND.
         *
         * (k.isReservering = 0 OR k.isReservering IS NULL)
         * AND
         * (k2.isReservering = 0 OR k2.isReservering IS NULL)
         * GROUP BY p.id
         * ;
         */
        $builder = $this->repository->createQueryBuilder('project');

        $startDateMin12Month = clone $startdate;
        $startDateMin12Month = $startDateMin12Month->modify('-12 months');

        $builder

            ->select('COUNT(kActief.id) AS aantalActief, COUNT(kGestart.id) AS aantalGestart, project.naam AS groep')
            ->leftJoin('project.huuraanbiedingen', 'aanbod')
            ->leftJoin('aanbod.huurovereenkomst', 'kActief', Join::WITH,
                '(kActief.startdatum >= :startdatumMin12Month AND  kActief.einddatum >= :startdatum OR kActief.einddatum IS NULL)
                AND kActief.startdatum <= :einddatum AND (kActief.afsluitdatum >= :startdatum OR kActief.afsluitdatum IS NULL)  
                ')
            ->leftJoin('aanbod.huurovereenkomst', 'kGestart', Join::WITH, 'kGestart.startdatum >= :startdatum AND kGestart.startdatum <= :einddatum')
            ->leftJoin('kActief.afsluiting', 'kaAfsluiting')
            ->leftJoin('kGestart.afsluiting', 'ksAfsluiting')
            ->andWhere('( (kaAfsluiting.tonen IS NULL OR kaAfsluiting.tonen = 1) AND (ksAfsluiting.tonen IS NULL or ksAfsluiting.tonen = 1) )')
            ->andWhere('( (kActief.isReservering  = 0 OR kActief.isReservering IS NULL) AND kGestart.isReservering = 0 OR kGestart.isReservering IS NULL )')
            ->setParameter(':startdatum', $startdate)
            ->setParameter(':einddatum', $enddate)
            ->setParameter(':startdatumMin12Month', $startDateMin12Month)
            ->groupBy('project.naam')
        ;

        // $sql = SqlExtractor::getFullSQL($builder->getQuery());

        $result = $builder->getQuery()->getResult();

        return $result;
    }
}
