<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Traject;
use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class TrajectDao extends AbstractDao implements TrajectDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'traject.id',
            'klant.achternaam',
            'trajectsoort.naam',
            'resultaatgebiedsoort.naam',
            'begeleider.naam',
            'traject.startdatum',
            'rapportage.datum',
            'traject.afsluitdatum',
        ],
    ];

    protected $class = Traject::class;

    protected $alias = 'traject';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin($this->alias.'.deelnemer', 'deelnemer')
            ->innerJoin('deelnemer.klant', 'klant')
            ->innerJoin($this->alias.'.soort', 'trajectsoort')
            ->innerJoin($this->alias.'.resultaatgebied', 'resultaatgebied')
            ->innerJoin('resultaatgebied.soort', 'resultaatgebiedsoort')
            ->leftJoin($this->alias.'.rapportages', 'rapportage')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
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
