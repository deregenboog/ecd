<?php

namespace IzBundle\Service;

use IzBundle\Entity\Project;
use AppBundle\Service\AbstractDao;

class ProjectDao extends AbstractDao implements ProjectDaoInterface
{
    protected $paginationOptions = [
        'defaultSortFieldName' => 'project.naam',
        'defaultSortDirection' => 'asc',
        'sortFieldWhitelist' => [
            'project.naam',
            'project.startdatum',
            'project.einddatum',
            'project.heeftKoppelingen',
            'project.prestatieStrategy',
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
}
