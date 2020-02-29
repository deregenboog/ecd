<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Project;
use DagbestedingBundle\Form\ProjectType;
use DagbestedingBundle\Service\ProjectDaoInterface;
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
     * @var ProjectDaoInterface
     *
     */
    protected $dao;

    public function __construct()
    {
        $this->dao = $this->get("DagbestedingBundle\Service\ProjectDao");
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
