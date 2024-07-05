<?php

namespace ScipBundle\Service;

use AppBundle\Entity\Medewerker;
use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Knp\Component\Pager\Pagination\PaginationInterface;
use ScipBundle\Entity\Project;

class ProjectDao extends AbstractDao implements ProjectDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'project.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'project.id',
            'project.naam',
            'project.kpl',
            'project.actief',
        ],
    ];

    protected $class = Project::class;

    protected $alias = 'project';

    /**
     * {inheritdoc}.
     */
    public function findAll($page = null, ?FilterInterface $filter = null): PaginationInterface
    {
        $builder = $this->repository->createQueryBuilder($this->alias);

        return parent::doFindAll($builder, $page, $filter);
    }

    /**
     * {inheritdoc}.
     */
    public function findByMedewerker(Medewerker $medewerker, $page = null, ?FilterInterface $filter = null): PaginationInterface
    {
        $builder = $this->repository->createQueryBuilder($this->alias)
            ->innerJoin($this->alias.'.toegangsrechten', 'toegangsrecht', 'WITH', 'toegangsrecht.medewerker = :medewerker')
            ->setParameter('medewerker', $medewerker)
        ;

        return parent::doFindAll($builder, $page, $filter);
    }

    /**
     * @return Project
     */
    public function findOneByKpl(string $kpl)
    {
        return $this->repository->findOneBy(['kpl' => $kpl]);
    }

    /**
     * {inheritdoc}.
     */
    public function create(Project $entity)
    {
        $this->doCreate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function update(Project $entity)
    {
        $this->doUpdate($entity);
    }

    /**
     * {inheritdoc}.
     */
    public function delete(Project $entity)
    {
        $this->doDelete($entity);
    }
}
