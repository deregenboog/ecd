<?php

namespace InloopBundle\Service;

use AppBundle\Doctrine\SqlExtractor;
use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\Query\Expr\Join;
use InloopBundle\Entity\Aanmelding;

class KlantDao extends AbstractDao implements KlantDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.voornaam',
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
        $builder = $this->getAllQueryBuilder($filter);
//        $sql = $builder->getQuery()->getSQL();
//        $params = $builder->getQuery()->getParameters();
        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
    }

    public function findAllAangemeld($page = null, FilterInterface $filter = null)
    {
        $builder = $this->getAllQueryBuilder($filter);
        $builder->andWhere("status INSTANCE OF  ".Aanmelding::class);
        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();

    }

    public function getAllQueryBuilder(FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select($this->alias.', intake, geslacht, eersteIntake, laatsteIntake, laatsteIntakeLocatie, gebruikersruimte')
            ->innerJoin('klant.huidigeStatus', 'status')
            ->leftJoin($this->alias.'.intakes', 'intake')//, "WITH","klant.eersteIntake = intake")
            ->leftJoin($this->alias.'.geslacht', 'geslacht')
            ->leftJoin($this->alias.'.laatsteIntake', 'laatsteIntake')
            ->leftJoin($this->alias.'.eersteIntake', 'eersteIntake')

            ->leftJoin('laatsteIntake.intakelocatie', 'laatsteIntakeLocatie')
            ->leftJoin('laatsteIntake.gebruikersruimte', 'gebruikersruimte')

            ->leftJoin('eersteIntake.intakelocatie', 'eersteIntakeLocatie')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }
//        $sql = SqlExtractor::getFullSQL($builder->getQuery());
        return $builder;
    }

    /**
     * @param Klant $klant
     *
     * @return Klant
     */
    public function create(Klant $entity)
    {
        $aanmelding = new Aanmelding($entity->getMedewerker());
        $aanmelding->setKlant($entity);
        $entity->setHuidigeStatus($aanmelding);

        return parent::doCreate($entity);
    }

    /**
     * @param Klant $klant
     *
     * @return Klant
     */
    public function update(Klant $entity)
    {
        return parent::doUpdate($entity);
    }
}
