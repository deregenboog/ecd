<?php

namespace MwBundle\Service;

use AppBundle\Filter\FilterInterface;
use AppBundle\Service\AbstractDao;
use Doctrine\ORM\QueryBuilder;
use MwBundle\Entity\Project;

class ProjectDao extends AbstractDao implements ProjectDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'project.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'project.id',
            'project.naam',
            'project.actief',
        ],
    ];

    protected $class = Project::class;

    protected $alias = 'project';

    public function create(Project $project)
    {
        $this->doCreate($project);
    }

    public function update(Project $project)
    {
        $this->doUpdate($project);
    }

    public function delete(Project $project)
    {
        $this->doDelete($project);
    }

    protected function doFindAll(QueryBuilder $builder, $page = 1, FilterInterface $filter = null)
    {
        $builder->innerJoin('project.medewerkers', 'medewerker');

        return parent::doFindAll($builder, $page, $filter);
    }
}
