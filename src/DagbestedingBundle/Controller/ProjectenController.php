<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Project;
use DagbestedingBundle\Form\ProjectType;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DagbestedingBundle\Service\ProjectDaoInterface;

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
     * @DI\Inject("dagbesteding.dao.project")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        return $this->redirectToIndex();
    }
}
