<?php

namespace MwBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Aanmelding;
use Doctrine\DBAL\Platforms\MySQL57Platform;

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
            ->select($this->alias.', intake , geslacht, laatsteIntake, laatsteIntakeLocatie, gebruikersruimte')
            ->addSelect('MAX(verslag.datum) AS datumLaatsteVerslag')
            ->addSelect('COUNT(DISTINCT verslag.id) AS aantalVerslagen')
            ->leftJoin($this->alias.'.huidigeStatus', 'status')
            ->leftJoin($this->alias.'.intakes', 'intake')
            ->leftJoin($this->alias.'.verslagen', 'verslag')
            ->leftJoin($this->alias.'.geslacht', 'geslacht')
            ->leftJoin($this->alias.'.laatsteIntake', 'laatsteIntake')
            ->leftJoin('laatsteIntake.intakelocatie', 'laatsteIntakeLocatie')
            ->leftJoin('laatsteIntake.gebruikersruimte', 'gebruikersruimte')
            ->groupBy($this->alias.'.id')
            ;
        /**
         * !!! LET OP TESTEN want live levert het een probleem op.
         * Sinds MySQL 5.7 is het verplicht alle select vleden in de group by te noemen. google: ONLY_FULL_GROUP_BY
         *
         * Live draait nog 5.6.x
         * Dit is niet compatible. Vandaar de versie check hier... Kan weg wanneer live naar 5.7 gaat.
         */
        $platform = $this->entityManager->getConnection()->getDatabasePlatform();
        if($platform instanceof MySQL57Platform)
        {
            $builder
                ->addGroupBy('intake')
                ->addGroupBy('verslag')
                ->addGroupBy('laatsteIntake')
                ->addGroupBy('laatsteIntakeLocatie')
                ->addGroupBy('gebruikersruimte')
            ;
        }


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
