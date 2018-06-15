<?php

namespace HsBundle\Service;

use HsBundle\Entity\Registratie;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Entity\Vrijwilliger;
use Doctrine\DBAL\Exception\DriverException;

class RegistratieDao extends AbstractDao implements RegistratieDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'registratie.datum+registratie.start',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'basisklant.achternaam+basisvrijwilliger.achternaam',
            'klant.achternaam',
            'werkgebied.naam',
            'activiteit.naam',
            'registratie.datum+registratie.start',
            'registratie.start',
            'registratie.eind',
        ],
    ];

    protected $class = Registratie::class;

    protected $alias = 'registratie';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select($this->alias.', klus, klant, werkgebied')
            ->innerJoin($this->alias.'.klus', 'klus')
            ->innerJoin('klus.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->innerJoin('registratie.activiteit', 'activiteit')
            ->innerJoin('registratie.arbeider', 'arbeider')
            ->leftJoin(Dienstverlener::class, 'dienstverlener', 'WITH', 'arbeider = dienstverlener')
            ->leftJoin('dienstverlener.klant', 'basisklant')
            ->leftJoin(Vrijwilliger::class, 'vrijwilliger', 'WITH', 'arbeider = vrijwilliger')
            ->leftJoin('vrijwilliger.vrijwilliger', 'basisvrijwilliger')
        ;

        return $this->doFindAll($builder, $page, $filter);
    }

    /**
     * {inheritdoc}.
     */
    public function find($id)
    {
        return parent::find($id);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Registratie $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Registratie $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Registratie $entity)
    {
        $this->doDelete($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function countUrenByStadsdeel(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->select('SUM(time_to_sec(time_diff(registratie.eind, registratie.start))/3600) AS aantal')
            ->addSelect('werkgebied.naam AS groep')
            ->innerJoin('registratie.klus', 'klus')
            ->leftJoin('klus.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->groupBy('groep')
        ;

        if ($start) {
            $builder->andWhere('registratie.datum >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('registratie.datum <= :end')->setParameter('end', $end);
        }

        // Contains MySQL-specific functions, so execute in try-catch-block.
        try {
            return $builder->getQuery()->getResult();
        } catch (DriverException $e) {
            return [];
        }
    }

    /**
     * {inheritdoc}.
     */
    public function countUrenByKlant(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->select('SUM(time_to_sec(time_diff(registratie.eind, registratie.start))/3600) AS aantal')
            ->addSelect("CONCAT_WS(' ', klant.voornaam, klant.tussenvoegsel, klant.achternaam) AS groep")
            ->innerJoin('registratie.klus', 'klus')
            ->leftJoin('klus.klant', 'klant')
            ->groupBy('groep')
        ;

        if ($start) {
            $builder->andWhere('registratie.datum >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('registratie.datum <= :end')->setParameter('end', $end);
        }

        // Contains MySQL-specific functions, so execute in try-catch-block.
        try {
            return $builder->getQuery()->getResult();
        } catch (DriverException $e) {
            return [];
        }
    }

    /**
     * {inheritdoc}.
     */
    public function countUrenByKlus(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->select('SUM(time_to_sec(time_diff(registratie.eind, registratie.start))/3600) AS aantal')
            ->addSelect("CONCAT(klus.startdatum, ' - ', CONCAT_WS(' ', klant.voornaam, klant.tussenvoegsel, klant.achternaam)) AS klusnaam")
            ->addSelect('activiteit.naam AS activiteitnaam')
            ->innerJoin('registratie.activiteit', 'activiteit')
            ->innerJoin('registratie.klus', 'klus')
            ->leftJoin('klus.klant', 'klant')
            ->groupBy('klusnaam, activiteitnaam')
        ;

        if ($start) {
            $builder->andWhere('registratie.datum >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('registratie.datum <= :end')->setParameter('end', $end);
        }

        // Contains MySQL-specific functions, so execute in try-catch-block.
        try {
            return $builder->getQuery()->getResult();
        } catch (DriverException $e) {
            return [];
        }
    }

    /**
     * {inheritdoc}.
     */
    public function countUrenByActiviteit(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->select('SUM(time_to_sec(time_diff(registratie.eind, registratie.start))/3600) AS aantal')
            ->addSelect('activiteit.naam AS groep')
            ->innerJoin('registratie.activiteit', 'activiteit')
            ->groupBy('groep')
        ;

        if ($start) {
            $builder->andWhere('registratie.datum >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('registratie.datum <= :end')->setParameter('end', $end);
        }

        // Contains MySQL-specific functions, so execute in try-catch-block.
        try {
            return $builder->getQuery()->getResult();
        } catch (DriverException $e) {
            return [];
        }
    }

    /**
     * {inheritdoc}.
     */
    public function countUrenByDienstverlener(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->select('SUM(time_to_sec(time_diff(registratie.eind, registratie.start))/3600) AS aantal')
            ->addSelect("CONCAT_WS(' ', klant.voornaam, klant.tussenvoegsel, klant.achternaam) AS groep")
            ->innerJoin('registratie.arbeider', 'arbeider')
            ->innerJoin(Dienstverlener::class, 'dienstverlener', 'WITH', 'arbeider = dienstverlener')
            ->innerJoin('dienstverlener.klant', 'klant')
            ->groupBy('groep')
        ;

        if ($start) {
            $builder->andWhere('registratie.datum >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('registratie.datum <= :end')->setParameter('end', $end);
        }

        // Contains MySQL-specific functions, so execute in try-catch-block.
        try {
            return $builder->getQuery()->getResult();
        } catch (DriverException $e) {
            return [];
        }
    }

    /**
     * {inheritdoc}.
     */
    public function countUrenByVrijwilliger(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->select('SUM(time_to_sec(time_diff(registratie.eind, registratie.start))/3600) AS aantal')
            ->addSelect("CONCAT_WS(' ', basisvrijwilliger.voornaam, basisvrijwilliger.tussenvoegsel, basisvrijwilliger.achternaam) AS groep")
            ->innerJoin('registratie.arbeider', 'arbeider')
            ->innerJoin(Vrijwilliger::class, 'vrijwilliger', 'WITH', 'arbeider = vrijwilliger')
            ->innerJoin('vrijwilliger.vrijwilliger', 'basisvrijwilliger')
            ->groupBy('groep')
        ;

        if ($start) {
            $builder->andWhere('registratie.datum >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('registratie.datum <= :end')->setParameter('end', $end);
        }

        // Contains MySQL-specific functions, so execute in try-catch-block.
        try {
            return $builder->getQuery()->getResult();
        } catch (DriverException $e) {
            return [];
        }
    }
}
