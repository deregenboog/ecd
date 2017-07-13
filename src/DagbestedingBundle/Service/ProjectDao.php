<?php

namespace DagbestedingBundle\Service;

use AppBundle\Service\AbstractDao;
use DagbestedingBundle\Entity\Project;
use AppBundle\Filter\FilterInterface;

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

    public function findAll($page = null, FilterInterface $filter = null)
    {
        return parent::findAll($page, $filter);
    }

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
}
