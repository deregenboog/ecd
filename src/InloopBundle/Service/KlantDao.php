<?php

namespace InloopBundle\Service;

use AppBundle\Service\AbstractDao;
use AppBundle\Filter\FilterInterface;
use AppBundle\Entity\Klant;

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
        ],
    ];

    protected $class = Klant::class;

    protected $alias = 'klant';

    public function findAll($page = null, FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select($this->alias.', intake, geslacht, laatsteIntake, laatsteIntakeLocatie, gebruikersruimte')
            ->innerJoin($this->alias.'.intakes', 'intake')
            ->leftJoin($this->alias.'.geslacht', 'geslacht')
            ->leftJoin($this->alias.'.laatsteIntake', 'laatsteIntake')
            ->leftJoin('laatsteIntake.intakelocatie', 'laatsteIntakeLocatie')
            ->leftJoin('laatsteIntake.gebruikersruimte', 'gebruikersruimte')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }
}
