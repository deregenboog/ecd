<?php

namespace ClipBundle\Service;

use AppBundle\Service\AbstractDao;
use ClipBundle\Entity\Vraag;
use AppBundle\Filter\FilterInterface;
use Doctrine\ORM\QueryBuilder;

class VraagDao extends AbstractDao implements VraagDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'vraag.startdatum',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'vraag.id',
            'vraag.startdatum',
            'vraag.afsluitdatum',
            'vraagsoort.naam',
            'medewerker.voornaam',
            'klant.achternaam',
        ],
    ];

    protected $class = Vraag::class;

    protected $alias = 'vraag';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin($this->alias.'.soort', 'vraagsoort')
            ->innerJoin($this->alias.'.medewerker', 'medewerker')
            ->innerJoin($this->alias.'.client', 'client')
            ->innerJoin('client.klant', 'klant')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Vraag $vraag)
    {
        $this->doCreate($vraag);
    }

    public function update(Vraag $vraag)
    {
        $this->doUpdate($vraag);
    }

    public function delete(Vraag $vraag)
    {
        $this->doDelete($vraag);
    }

    public function countByCommunicatiekanaal($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, communicatiekanaal.naam AS groep")
            ->innerJoin("{$this->alias}.communicatiekanaal", 'communicatiekanaal')
            ->groupBy('communicatiekanaal.naam')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByHulpvrager($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, hulpvrager.naam AS groep")
            ->innerJoin("{$this->alias}.hulpvrager", 'hulpvrager')
            ->groupBy('hulpvrager.naam')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByLeeftijdscategorie($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, leeftijdscategorie.naam AS groep")
            ->innerJoin("{$this->alias}.leeftijdscategorie", 'leeftijdscategorie')
            ->groupBy('leeftijdscategorie.naam')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByNationaliteit($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, nationaliteit.naam AS groep")
            ->innerJoin("{$this->alias}.client", 'client')
            ->innerJoin('client.klant', 'klant')
            ->innerJoin('klant.nationaliteit', 'nationaliteit')
            ->groupBy('nationaliteit.naam')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByWoonplaats($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, klant.plaats AS groep")
            ->innerJoin("{$this->alias}.client", 'client')
            ->innerJoin('client.klant', 'klant')
            ->groupBy('klant.plaats')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByGeslacht($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, geslacht.volledig AS groep")
            ->innerJoin("{$this->alias}.client", 'client')
            ->innerJoin('client.klant', 'klant')
            ->innerJoin('klant.geslacht', 'geslacht')
            ->groupBy('geslacht.volledig')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByViacategorie($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, viacategorie.naam AS groep")
            ->innerJoin("{$this->alias}.client", 'client')
            ->innerJoin('client.viacategorie', 'viacategorie')
            ->groupBy('viacategorie.naam')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByGeboorteland($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, geboorteland.land AS groep")
            ->innerJoin("{$this->alias}.client", 'client')
            ->innerJoin('client.klant', 'klant')
            ->innerJoin('klant.land', 'geboorteland')
            ->groupBy('geboorteland.land')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    public function countByVraagsoort($fase, \DateTime $startdate, \DateTime $enddate)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("COUNT({$this->alias}.id) AS aantal, vraagsoort.naam AS groep")
            ->innerJoin("{$this->alias}.soort", 'vraagsoort')
            ->groupBy('vraagsoort.naam')
        ;

        $this->applyFilter($builder, $fase, $startdate, $enddate);

        return $builder->getQuery()->getResult();
    }

    protected function applyFilter(QueryBuilder $builder, $fase, \DateTime $startdate, \DateTime $enddate)
    {
        switch ($fase) {
            case self::FASE_BEGINSTAND:
                $builder
                    ->andWhere("{$this->alias}.startdatum < :startdate")
                    ->andWhere("{$this->alias}.afsluitdatum IS NULL OR {$this->alias}.afsluitdatum >= :startdate")
                    ->setParameter('startdate', $startdate)
                ;
                break;
            case self::FASE_GESTART:
                $builder
                    ->andWhere("{$this->alias}.startdatum BETWEEN :startdate AND :enddate")
                    ->setParameters([
                        'startdate' => $startdate,
                        'enddate' => $enddate,
                    ])
                ;
                break;
            case self::FASE_AFGESLOTEN:
                $builder
                ->andWhere("{$this->alias}.afsluitdatum BETWEEN :startdate AND :enddate")
                    ->setParameters([
                        'startdate' => $startdate,
                        'enddate' => $enddate,
                    ])
                ;
                break;
            case self::FASE_EINDSTAND:
                $builder
                ->andWhere("{$this->alias}.startdatum < :enddate")
                ->andWhere("{$this->alias}.afsluitdatum IS NULL OR {$this->alias}.afsluitdatum > :enddate")
                    ->setParameter('enddate', $enddate)
                ;
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Ongeldige fase "%s"', $fase));
        }
    }
}
