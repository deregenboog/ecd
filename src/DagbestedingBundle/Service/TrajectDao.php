<?php

namespace DagbestedingBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Traject;
use Doctrine\ORM\QueryBuilder;

class TrajectDao extends AbstractDao implements TrajectDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.achternaam',
            'trajectsoort.naam',
            'resultaatgebiedsoort.naam',
            'trajectcoach.naam',
            'traject.startdatum',
            'traject.evaluatiedatum',
            'traject.afsluitdatum',
        ],
        'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = Traject::class;

    protected $alias = 'traject';

    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin($this->alias.'.trajectcoach', 'trajectcoach')
            ->innerJoin($this->alias.'.deelnemer', 'deelnemer')
            ->innerJoin($this->alias.'.deelnames', 'deelnames')
            ->innerJoin('deelnemer.klant', 'klant')
            ->innerJoin($this->alias.'.soort', 'trajectsoort')
            ->innerJoin($this->alias.'.resultaatgebied', 'resultaatgebied')
            ->innerJoin('resultaatgebied.soort', 'resultaatgebiedsoort')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Traject $traject)
    {
        $this->doCreate($traject);
    }

    public function update(Traject $traject)
    {
        $this->doUpdate($traject);
    }

    public function delete(Traject $traject)
    {
        $this->doDelete($traject);
    }

    public function countByAfsluiting($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select('COUNT('.$this->alias.') AS aantal, afsluiting.naam AS groep')
            ->innerJoin($this->alias.'.afsluiting', 'afsluiting')
            ->groupBy('afsluiting.naam')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function getVerlengingenPerTrajectcoach(\DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder('traject');
        $builder->select('appKlant.achternaam AS naam, trajectcoach.naam AS trajectCoach, traject.einddatum')
            ->innerJoin('traject.trajectcoach', 'trajectcoach')
            ->innerJoin('traject.deelnemer', 'deelnemer')
            ->innerJoin('deelnemer.klant', 'appKlant')
            ->where('traject.einddatum IS NULL OR traject.einddatu <= :two_months_from_now')
            ->groupBy('traject.deelnemer')
            ->orderBy('trajectcoach.naam', 'ASC')
            ->setParameter('two_months_from_now', new \DateTime('+2 MONTHS'))
        ;

        $this->applyFilter($builder, self::FASE_GESTART, $startdate, $enddate);
        $res = $builder->getQuery()->getResult();

        //        $sql = $builder->getQuery()->getSQL();
        return $res;
    }

    protected function applyFilter(QueryBuilder $builder, $fase, \DateTime $startdate, \DateTime $enddate)
    {
        switch ($fase) {
            case self::FASE_BEGINSTAND:
                $builder
                    ->where($this->alias.'.startdatum < :startdate')
                    ->andWhere($this->alias.'.afsluitdatum IS NULL OR '.$this->alias.'.afsluitdatum >= :startdate')
                    ->setParameter('startdate', $startdate)
                ;
                break;
            case self::FASE_GESTART:
                $builder
                    ->where($this->alias.'.startdatum BETWEEN :startdate AND :enddate')
                    ->setParameters([
                        'startdate' => $startdate,
                        'enddate' => $enddate,
                    ])
                ;
                break;
            case self::FASE_GESTOPT:
                $builder
                    ->where($this->alias.'.afsluitdatum BETWEEN :startdate AND :enddate')
                    ->setParameters([
                        'startdate' => $startdate,
                        'enddate' => $enddate,
                    ])
                ;
                break;
            case self::FASE_EINDSTAND:
                $builder
                    ->where($this->alias.'.startdatum < :enddate')
                    ->andWhere($this->alias.'.afsluitdatum IS NULL OR '.$this->alias.'.afsluitdatum > :enddate')
                    ->setParameter('enddate', $enddate)
                ;
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Ongeldige fase "%s"', $fase));
        }
    }
}
