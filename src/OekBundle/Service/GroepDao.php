<?php

namespace OekBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use OekBundle\Entity\Groep;

class GroepDao extends AbstractDao implements GroepDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'groep.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'groep.id',
            'groep.naam',
            'groep.aantalBijeenkomsten',
        ],
    ];

    protected $class = Groep::class;

    protected $alias = 'groep';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder('groep')
            ->select(
                'groep',
                'COUNT(DISTINCT lidmaatschap) AS lidmaatschappen',
                'COUNT(DISTINCT training) AS trainingen'
            )
            ->leftJoin('groep.lidmaatschappen', 'lidmaatschap')
            ->leftJoin('groep.trainingen', 'training')
            ->groupBy('groep')
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
    public function create(Groep $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Groep $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Groep $entity)
    {
        $this->doDelete($entity);
    }
}
