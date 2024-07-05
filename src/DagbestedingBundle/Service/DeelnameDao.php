<?php

namespace DagbestedingBundle\Service;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Deelname;

class DeelnameDao extends AbstractDao implements DeelnameDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'klant.achternaam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'klant.id',
            'klant.voornaam',
            'klant.achternaam',
        ],
        'wrap-queries' => true, // because of HAVING clause in filter
    ];

    protected $class = Deelname::class;

    protected $alias = 'deelname';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, ?FilterInterface $filter = null)
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin($this->alias.'.traject', 'traject')
            ->innerJoin('traject.deelnemer', 'deelnemer')
            ->innerJoin('deelnemer.klant', 'klant')
        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    /**
     * {inheritdoc}.
     */
    public function findByMedewerker(Medewerker $medewerker, $page = null, ?FilterInterface $filter = null): array
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin($this->alias.'.deelnemer', 'deelnemer')
            ->innerJoin('deelnemer.klant', 'klant')
            ->innerJoin('deelname.project', 'project')
            ->innerJoin('project.toegangsrechten', 'toegangsrecht', 'WITH', 'toegangsrecht.medewerker = :medewerker')
            ->setParameter('medewerker', $medewerker)
        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Deelname $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Deelname $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Deelname $entity)
    {
        $this->doDelete($entity);
    }
}
