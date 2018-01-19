<?php

namespace HsBundle\Service;

use HsBundle\Entity\Registratie;
use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Entity\Vrijwilliger;

class RegistratieDao extends AbstractDao implements RegistratieDaoInterface
{
    protected $class = Registratie::class;

    protected $alias = 'registratie';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, FilterInterface $filter = null)
    {
        return parent::findAll($page, $filter);
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
            ->innerJoin('klus.klant', 'klant')
            ->leftJoin('klant.werkgebied', 'werkgebied')
            ->groupBy('groep')
        ;

        if ($start) {
            $builder->andWhere('registratie.datum >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('registratie.datum <= :end')->setParameter('end', $end);
        }

        return $builder->getQuery()->getResult();
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
            ->innerJoin('klus.klant', 'klant')
            ->groupBy('groep')
        ;

        if ($start) {
            $builder->andWhere('registratie.datum >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('registratie.datum <= :end')->setParameter('end', $end);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * {inheritdoc}.
     */
    public function countUrenByKlus(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->select('SUM(time_to_sec(time_diff(registratie.eind, registratie.start))/3600) AS aantal')
            ->addSelect("CONCAT(activiteit.naam, ' - ', CONCAT_WS(' ', klant.voornaam, klant.tussenvoegsel, klant.achternaam)) AS groep")
            ->innerJoin('registratie.klus', 'klus')
            ->innerJoin('klus.activiteit', 'activiteit')
            ->innerJoin('klus.klant', 'klant')
            ->groupBy('groep')
        ;

        if ($start) {
            $builder->andWhere('registratie.datum >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('registratie.datum <= :end')->setParameter('end', $end);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * {inheritdoc}.
     */
    public function countUrenByActiviteit(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->select('SUM(time_to_sec(time_diff(registratie.eind, registratie.start))/3600) AS aantal')
            ->addSelect("activiteit.naam AS groep")
            ->innerJoin('registratie.klus', 'klus')
            ->innerJoin('klus.activiteit', 'activiteit')
            ->groupBy('groep')
        ;

        if ($start) {
            $builder->andWhere('registratie.datum >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('registratie.datum <= :end')->setParameter('end', $end);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * {inheritdoc}.
     */
    public function countUrenByArbeider(\DateTime $start = null, \DateTime $end = null)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->select('SUM(time_to_sec(time_diff(registratie.eind, registratie.start))/3600) AS aantal')
            ->addSelect("CONCAT_WS(' ', klant.voornaam, klant.tussenvoegsel, klant.achternaam, basisvrijwilliger.voornaam, basisvrijwilliger.tussenvoegsel, basisvrijwilliger.achternaam) AS groep")
            ->innerJoin('registratie.arbeider', 'arbeider')
            ->leftJoin(Dienstverlener::class, 'dienstverlener', 'WITH', 'arbeider = dienstverlener')
            ->leftJoin(Vrijwilliger::class, 'vrijwilliger', 'WITH', 'arbeider = vrijwilliger')
            ->leftJoin('dienstverlener.klant', 'klant')
            ->leftJoin('vrijwilliger.vrijwilliger', 'basisvrijwilliger')
            ->groupBy('groep')
        ;

        if ($start) {
            $builder->andWhere('registratie.datum >= :start')->setParameter('start', $start);
        }

        if ($end) {
            $builder->andWhere('registratie.datum <= :end')->setParameter('end', $end);
        }

        return $builder->getQuery()->getResult();
    }
}
