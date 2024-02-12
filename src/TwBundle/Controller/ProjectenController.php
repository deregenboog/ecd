<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractController;

use TwBundle\Entity\Project;
use TwBundle\Form\ProjectType;
use TwBundle\Service\ProjectDao;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/projecten")
 */
class ProjectenController extends AbstractController
{
    protected $entityName = 'project';
    protected $entityClass = Project::class;
    protected $formClass = ProjectType::class;
    protected $baseRouteName = 'tw_projecten_';

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

    protected function redirectToView($entity)
    {
        return $this->redirectToIndex();
    }
}
