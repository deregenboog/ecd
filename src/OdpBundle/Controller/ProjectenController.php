<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\AbstractController;

use OdpBundle\Entity\Project;
use OdpBundle\Form\ProjectType;
use OdpBundle\Service\ProjectDao;
use JMS\DiExtraBundle\Annotation as DI;
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
    protected $baseRouteName = 'odp_projecten_';

    /**
     * @var ProjectDaoInterface
     *
     * @DI\Inject("OdpBundle\Service\ProjectDao")
     */
    protected $dao;

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
