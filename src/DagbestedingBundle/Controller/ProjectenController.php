<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Project;
use DagbestedingBundle\Form\ProjectType;
use DagbestedingBundle\Service\ProjectDao;
use DagbestedingBundle\Service\ProjectDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/projecten")
 */
class ProjectenController extends AbstractController
{
    protected $title = 'Projecten';
    protected $entityName = 'Project';
    protected $entityClass = Project::class;
    protected $formClass = ProjectType::class;
    protected $baseRouteName = 'dagbesteding_projecten_';

    /**
     * @var ProjectDao
     */
    protected $dao;

    /**
     * @param ProjectDao $dao
     */
    public function __construct(ProjectDao $dao)
    {
        $this->dao = $dao;
    }


    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
