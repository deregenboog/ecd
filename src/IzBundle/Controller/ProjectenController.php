<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Service\ProjectDaoInterface;
use IzBundle\Entity\Project;
use IzBundle\Form\ProjectType;
use IzBundle\Service\ProjectDao;
use JMS\DiExtraBundle\Annotation as DI;
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
    protected $baseRouteName = 'iz_projecten_';

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
