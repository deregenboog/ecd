<?php

namespace ScipBundle\Service;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use ScipBundle\Entity\Toegangsrecht;

class ToegangsrechtDao extends AbstractDao implements ToegangsrechtDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'medewerker.voornaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'medewerker.username',
            'medewerker.voornaam',
        ],
    ];

    protected $class = Toegangsrecht::class;

    protected $alias = 'toegangsrecht';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin($this->alias.'.medewerker', 'medewerker')
        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    public function findOneByMedewerker(Medewerker $medewerker): ?Toegangsrecht
    {
        return $this->repository->findOneBy(['medewerker' => $medewerker]);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Toegangsrecht $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Toegangsrecht $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Toegangsrecht $entity)
    {
        $this->doDelete($entity);
    }
}
