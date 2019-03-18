<?php

namespace MwBundle\Service;

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use InloopBundle\Entity\Aanmelding;

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
            ->select($this->alias.', intake, geslacht, laatsteIntake, laatsteIntakeLocatie, gebruikersruimte')
            ->addSelect('MAX(verslag.datum) AS datumLaatsteVerslag')
            ->addSelect('COUNT(DISTINCT verslag.id) AS aantalVerslagen')
            ->leftJoin($this->alias.'.intakes', 'intake')
            ->leftJoin($this->alias.'.verslagen', 'verslag')
            ->leftJoin($this->alias.'.geslacht', 'geslacht')
            ->leftJoin($this->alias.'.laatsteIntake', 'laatsteIntake')
            ->leftJoin('laatsteIntake.intakelocatie', 'laatsteIntakeLocatie')
            ->leftJoin('laatsteIntake.gebruikersruimte', 'gebruikersruimte')
            ->groupBy($this->alias.'.id');

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
