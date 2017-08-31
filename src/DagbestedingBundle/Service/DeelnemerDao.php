<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Deelnemer;
use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class DeelnemerDao extends AbstractDao implements DeelnemerDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'klant.werkgebied',
            'deelnemer.aanmelddatum',
            'deelnemer.afsluitdatum',
        ],
    ];

    protected $class = Deelnemer::class;

    protected $alias = 'deelnemer';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin('deelnemer.klant', 'klant')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Deelnemer $deelnemer)
    {
        $this->doCreate($deelnemer);
    }

    public function update(Deelnemer $deelnemer)
    {
        $this->doUpdate($deelnemer);
    }

    public function delete(Deelnemer $deelnemer)
    {
        $this->doDelete($deelnemer);
    }

    public function countByBegeleider($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select('COUNT('.$this->alias.') AS aantal, CONCAT_WS(\' \', medewerker.voornaam, medewerker.achternaam) AS groep')
            ->innerJoin($this->alias.'.trajecten', 'traject')
            ->innerJoin('traject.begeleider', 'begeleider')
            ->innerJoin('begeleider.medewerker', 'medewerker')
            ->groupBy('medewerker.id')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByLocatie($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select('COUNT('.$this->alias.') AS aantal, locatie.naam AS groep')
            ->innerJoin($this->alias.'.trajecten', 'traject')
            ->innerJoin('traject.locaties', 'locatie')
            ->groupBy('locatie.naam')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByProject($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select('COUNT('.$this->alias.') AS aantal, project.naam AS groep')
            ->innerJoin($this->alias.'.trajecten', 'traject')
            ->innerJoin('traject.projecten', 'project')
            ->groupBy('project.naam')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByResultaatgebiedsoort($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select('COUNT('.$this->alias.') AS aantal, soort.naam AS groep')
            ->innerJoin($this->alias.'.trajecten', 'traject')
            ->innerJoin('traject.resultaatgebied', 'resultaatgebied')
            ->innerJoin('resultaatgebied.soort', 'soort')
            ->groupBy('soort.naam')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    protected function applyFilter(QueryBuilder $builder, $fase, \DateTime $startdate, \DateTime $enddate)
    {
        switch ($fase) {
            case self::FASE_BEGINSTAND:
                $builder
                    ->where('traject.startdatum < :startdate')
                    ->andWhere('traject.einddatum IS NULL OR traject.einddatum >= :startdate')
                    ->setParameter('startdate', $startdate)
                ;
                break;
            case self::FASE_GESTART:
                $builder
                    ->where('traject.startdatum BETWEEN :startdate AND :enddate')
                    ->setParameters([
                        'startdate' => $startdate,
                        'enddate' => $enddate,
                    ])
                ;
                break;
            case self::FASE_GESTOPT:
                $builder
                    ->where('traject.einddatum BETWEEN :startdate AND :enddate')
                    ->setParameters([
                        'startdate' => $startdate,
                        'enddate' => $enddate,
                    ])
                ;
                break;
            case self::FASE_EINDSTAND:
                $builder
                    ->where('traject.startdatum < :enddate')
                    ->andWhere('traject.einddatum IS NULL OR traject.einddatum > :enddate')
                    ->setParameter('enddate', $enddate)
                ;
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Ongeldige fase "%s"', $fase));
        }
    }
}
