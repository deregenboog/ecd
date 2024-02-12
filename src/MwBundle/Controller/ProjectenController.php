<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\Project;
use MwBundle\Form\ProjectType;
use MwBundle\Service\ProjectDao;
use MwBundle\Service\ProjectDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/projecten")
 */
class ProjectenController extends AbstractController
{
    protected $title = 'Projecten';
    protected $entityName = 'project';
    protected $entityClass = Project::class;
    protected $formClass = ProjectType::class;
    protected $baseRouteName = 'mw_projecten_';

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
