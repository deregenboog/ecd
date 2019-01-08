<?php

namespace InloopBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Aanmelding;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\RecenteRegistratie;
use InloopBundle\Entity\Registratie;
use InloopBundle\Filter\RegistratieFilter;

class RegistratieDao extends AbstractDao implements RegistratieDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'registratie.binnen',
        'defaultSortDirection' => 'desc',
        'sortFieldWhitelist' => [
            'registratie.binnen',
            'registratie.buiten',
            'registratie.douche',
            'registratie.kleding',
            'registratie.maaltijd',
            'registratie.veegploeg',
            'registratie.activering',
            'registratie.mw',
            'klant.id',
            'klant.voornaam',
            'klant.achternaam',
            'geslacht.volledig',
            'locatie.naam',
        ],
    ];

    protected $class = Registratie::class;

    protected $alias = 'registratie';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, klant, locatie, geslacht")
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->innerJoin("{$this->alias}.locatie", 'locatie')
            ->leftJoin('klant.geslacht', 'geslacht')
            ->where("registratie.binnen >= '2017-01-01 00:00:00'")
        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    public function findLatestByKlantAndLocatie(Klant $klant, Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin("{$this->alias}.klant", 'klant', 'WITH', 'klant = :klant')
            ->innerJoin("{$this->alias}.locatie", 'locatie', 'WITH', 'locatie = :locatie')
            ->orderBy("{$this->alias}.binnen", 'DESC')
            ->setParameters([
                'klant' => $klant,
                'locatie' => $locatie,
            ])
            ->setMaxResults(1)
        ;

        return $builder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param bool $type the value of either self::TYPE_DAY of self::TYPE_NIGHT
     *
     * @return Registratie[]
     */
    public function findAutoCheckoutCandidates($type)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin("{$this->alias}.locatie", 'locatie')
            ->where("{$this->alias}.closed = false AND locatie.nachtopvang = :type")
            ->orWhere("{$this->alias}.buiten < {$this->alias}.binnen")
            ->setParameter('type', (bool) $type)
        ;

        return $builder->getQuery()->getResult();
    }

    public function create(Registratie $entity)
    {
        return parent::doCreate($entity);
    }

    public function update(Registratie $entity)
    {
        return parent::doUpdate($entity);
    }

    public function delete(Registratie $entity)
    {
        $this->removeFromQueues($entity);

        return parent::doDelete($entity);
    }

    public function checkout(Registratie $registratie, \DateTime $time = null)
    {
        if ($registratie->getBuiten()) {
            return false;
        }

        if (!$time) {
            $time = new \DateTime();
        }

        $this->removeFromQueues($registratie);
        $registratie->setBuiten($time);
        $this->update($registratie);

        return true;
    }

    public function removeFromQueues(Registratie $registratie)
    {
        if ($registratie->getDouche() > 0) {
            $registratie->setDouche(0);
            $this->update($registratie);
            $this->reorderShowerQueue($registratie->getLocatie());
        }

        if ($registratie->getMw() > 0) {
            $registratie->setMw(0);
            $this->update($registratie);
            $this->reorderMwQueue($registratie->getLocatie());
        }
    }

    public function delKlantFromShowerList($registratie_id, &$registraties, &$registratie_data)
    {
        $registraties_list = [];

        foreach ($registraties as $key => $registratie) {
            if ($registratie['Registratie']['douche'] > 0) {
                $registraties_list[$registratie['Registratie']['id']] = $registratie['Registratie']['douche'];
            }
        }

        asort($registraties_list);
        $r_to_save = [];

        if ($registratie_data['Registratie']['douche'] > 0) {
            unset($registraties_list[$registratie_id]);
            $r_to_save[$registratie_data['Registratie']['id']]['id'] = $registratie_data['Registratie']['id'];
            $r_to_save[$registratie_data['Registratie']['id']]['douche'] = -1;
            $inc = 1;
            foreach ($registraties_list as $key => $value) {
                $r_to_save[$key]['id'] = $key;
                $r_to_save[$key]['douche'] = $inc;
                ++$inc;
            }
        } elseif (-1 == $registratie_data['Registratie']['douche']) {
            $r_to_save[$registratie_data['Registratie']['id']]['id'] = $registratie_data['Registratie']['id'];
            $r_to_save[$registratie_data['Registratie']['id']]['douche'] = 0;
        }
        $this->saveAll($r_to_save);
        foreach ($registraties as $r_key => $registratie_value) {
            foreach ($r_to_save as $registratie_saved) {
                if ($registratie_value['Registratie']['id'] == $registratie_saved['id']) {
                    $registraties[$r_key]['Registratie']['douche'] = $registratie_saved['douche'];
                }
            }
        }

        return $registraties;
    }

    public function checkoutKlantFromAllLocations(Klant $klant)
    {
        $registraties = $this->repository->createQueryBuilder('registratie')
            ->where('registratie.klant = :klant')
            ->andWhere('registratie.buiten IS NULL')
            ->setParameter('klant', $klant)
            ->getQuery()
            ->getResult()
        ;

        foreach ($registraties as $registratie) {
            $this->checkOut($registratie);
        }
    }

    public function findActiveByLocatie(Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->leftJoin('registratie.klant', 'klant')
            ->andwhere('registratie.locatie = :locatie')
            ->andwhere('registratie.closed = false')
            ->setParameter('locatie', $locatie)
        ;

        if ($locatie->isGebruikersruimte()) {
            $builder
                ->leftJoin('klant.laatsteIntake', 'intake')
                ->orderBy('intake.magGebruiken', 'ASC')
                ->addOrderBy('registratie.created', 'DESC')
            ;
        }

        return $builder->getQuery()->getResult();
    }

    public function findActiveGebruikersruimteByLocatie(Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->leftJoin('registratie.klant', 'klant')
            ->innerJoin('klant.laatsteIntake', 'intake', 'WITH', 'intake.magGebruiken = true')
            ->andwhere('registratie.locatie = :locatie')
            ->andwhere('registratie.closed = false')
            ->setParameter('locatie', $locatie)
            ->orderBy('intake.magGebruiken', 'ASC')
            ->addOrderBy('registratie.created', 'DESC')
        ;

        return $builder->getQuery()->getResult();
    }

    public function getActiveRegistraties(Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->leftJoin('registratie.klant', 'klant')
            ->andwhere('registratie.locatie = :locatie')
            ->andwhere('registratie.closed = false')
            ->setParameter('locatie', $locatie)
        ;

        if ($locatie->isGebruikersruimte()) {
            $builder
                ->leftJoin('klant.laatsteIntake', 'intake')
                ->orderBy('intake.magGebruiken', 'ASC')
                ->addOrderBy('registratie.created', 'DESC')
            ;
        }

        $regularKlanten = $builder->getQuery()->getResult();

        if ($locatie->isGebruikersruimte()) {
            $gebruikers = [];
            foreach ($regularKlanten as $registratie) {
                if (!$registratie->getKlant()->getLaatsteIntake()->isMagGebruiken()) {
                    continue;
                }

                array_unshift($gebruikers, $klant);
            }
        }

        return [$regularKlanten, $gebruikers];
    }

    public function findActive($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, locatie, klant, schorsing, opmerking")
            ->innerJoin("{$this->alias}.locatie", 'locatie')
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->leftJoin('klant.schorsingen', 'schorsing')
            ->leftJoin('klant.opmerkingen', 'opmerking')
            ->where("{$this->alias}.closed = false")
        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    public function findHistory($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, locatie, klant, huidigeStatus, schorsing, opmerking")
            ->innerJoin(RecenteRegistratie::class, 'recent', 'WITH', 'recent.registratie = registratie')
            ->innerJoin("{$this->alias}.locatie", 'locatie')
            ->innerJoin("{$this->alias}.klant", 'klant')
            ->leftJoin('klant.huidigeStatus', 'huidigeStatus', 'WITH', 'huidigeStatus INSTANCE OF '.Aanmelding::class)
            ->leftJoin('klant.schorsingen', 'schorsing')
            ->leftJoin('klant.opmerkingen', 'opmerking')
            ->where("{$this->alias}.closed = true")
        ;

        $this->paginationOptions['defaultSortFieldName'] = 'registratie.buiten';

        return parent::doFindAll($builder, $page, $filter);
    }

    public function findShowerQueue(Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->innerJoin('registratie.locatie', 'locatie')
            ->where('registratie.closed = false')
            ->orderBy('registratie.douche')
            ->setParameter('locatie', $locatie)
        ;

        $filter = new RegistratieFilter($locatie);
        $filter->douche = true;
        $filter->applyTo($builder);

        return $builder->getQuery()->getResult();
    }

    public function reorderShowerQueue(Locatie $locatie)
    {
        $queue = $this->findShowerQueue($locatie);
        foreach ($queue as $i => $registratie) {
            $registratie->setDouche(1 + $i);
        }
        $this->entityManager->flush();
    }

    public function findMwQueue(Locatie $locatie)
    {
        $builder = $this->repository->createQueryBuilder('registratie')
            ->innerJoin('registratie.locatie', 'locatie')
            ->where('registratie.closed = false')
            ->orderBy('registratie.mw')
            ->setParameter('locatie', $locatie)
        ;

        $filter = new RegistratieFilter($locatie);
        $filter->mw = true;
        $filter->applyTo($builder);

        return $builder->getQuery()->getResult();
    }

    public function reorderMwQueue(Locatie $locatie)
    {
        $queue = $this->findMwQueue($locatie);
        foreach ($queue as $i => $registratie) {
            $registratie->setMw(1 + $i);
        }
        $this->entityManager->flush();
    }
}
