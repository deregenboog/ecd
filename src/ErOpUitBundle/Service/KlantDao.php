<?php

namespace ErOpUitBundle\Service;

use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use ErOpUitBundle\Entity\Klant;

class KlantDao extends AbstractDao implements KlantDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'appKlant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'appKlant.achternaam',
            'appKlant.id',
            'appKlant.voornaam',
            'klant.inschrijfdatum',
            'klant.uitschrijfdatum',
            'werkgebied.naam',
        ],
    ];

    protected $class = Klant::class;

    protected $alias = 'klant';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->select("{$this->alias}, appKlant, werkgebied")
            ->innerJoin($this->alias.'.klant', 'appKlant')
            ->leftJoin('appKlant.werkgebied', 'werkgebied')
        ;

        if ($filter) {
            $filter->applyTo($builder);
        }

        if ($page) {
            return $this->paginator->paginate($builder, $page, $this->itemsPerPage, $this->paginationOptions);
        }

        return $builder->getQuery()->getResult();
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
    public function findOneByKlant(AppKlant $appKlant)
    {
        return $this->repository->findOneBy(['klant' => $appKlant]);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Klant $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Klant $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Klant $entity)
    {
        $this->doDelete($entity);
    }
}
