<?php

namespace UhkBundle\Controller;

use AppBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UhkBundle\Entity\Project;
use UhkBundle\Form\ProjectType;
use UhkBundle\Service\ProjectDaoInterface;

/**
 * @Route("/admin/projecten")
 */
class ProjectenController extends AbstractController
{
    protected $entityName = 'project';
    protected $entityClass = Project::class;
    protected $formClass = ProjectType::class;
    protected $baseRouteName = 'uhk_projecten_';

    /**
     * @var ProjectDaoInterface
     */
    protected $dao;

    public function __construct(ProjectDaoInterface $dao)
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
