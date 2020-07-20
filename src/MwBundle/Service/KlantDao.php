<?php

namespace MwBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Aanmelding;
use Doctrine\DBAL\Platforms\MySQL57Platform;
use InloopBundle\Entity\Locatie;

class KlantDao extends AbstractDao implements KlantDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.achternaam',
            'klant.geboortedatum',
            'geslacht.volledig',
            'gebruikersruimte.naam',
            'laatsteIntakeLocatie.naam',
            'laatsteIntake.intakedatum',
            'datumLaatsteVerslag',
            'aantalVerslagen',
        ],
        'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = Klant::class;

    protected $alias = 'klant';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select($this->alias.', intake , geslacht, laatsteIntake, laatsteIntakeLocatie, gebruikersruimte,huidigeMwStatus')
//            ->addSelect('MAX(verslag.datum) AS datumLaatsteVerslag')
//            ->addSelect('COUNT(DISTINCT verslag.id) AS aantalVerslagen')
            ->addSelect('\'2020-07-01\' AS datumLaatsteVerslag')
                ->addSelect('1 AS aantalVerslagen')
            ->leftJoin($this->alias.'.huidigeStatus', 'status')
            ->leftJoin($this->alias.'.intakes', 'intake')
            ->leftJoin($this->alias.'.verslagen', 'verslag')
            ->leftJoin($this->alias.'.geslacht', 'geslacht')
            ->leftJoin($this->alias.'.laatsteIntake', 'laatsteIntake')
            ->leftJoin($this->alias.'.huidigeMwStatus', 'huidigeMwStatus')
            ->leftJoin('laatsteIntake.intakelocatie', 'laatsteIntakeLocatie')
            ->leftJoin('laatsteIntake.gebruikersruimte', 'gebruikersruimte')
            ->groupBy($this->alias.'.id')
            ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function countResultatenPerLocatie($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('klant')
            /**
             * SELECT DISTINCT (klanten.id), mw_dossier_statussen.class, mw_resultaten.naam FROM klanten LEFT JOIN verslagen ON verslagen.klant_id = klanten.id
            LEFT JOIN mw_dossier_statussen ON mw_dossier_statussen.id = klanten.huidigeMwStatus_id
            LEFT JOIN mw_afsluiting_resultaat ON mw_afsluiting_resultaat.afsluiting_id = mw_dossier_statussen.id
            INNER JOIN mw_resultaten ON mw_resultaten.id = mw_afsluiting_resultaat.resultaat_id
            WHERE verslagen.id IS NOT NULL
            AND mw_dossier_statussen.class IN ('Afsluiting')
             */
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function create(Klant $entity)
    {
        $aanmelding = new Aanmelding($entity, $entity->getMedewerker());
        $entity->setHuidigeStatus($aanmelding);

        return parent::doCreate($entity);
    }

    public function update(Klant $entity)
    {
        $this->doUpdate($entity);
    }

    public function delete(Klant $entity)
    {
        $this->doDelete($entity);
    }

}
