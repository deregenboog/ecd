<?php

namespace UhkBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Form\ConfirmationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UhkBundle\Entity\Project;
use UhkBundle\Form\ProjectType;
use UhkBundle\Service\DeelnemerDaoInterface;
use UhkBundle\Service\ProjectDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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

    /**
     * @var DeelnemerDaoInterface
     */
    protected $daoDeelnemers;

    public function __construct(ProjectDaoInterface $dao, DeelnemerDaoInterface $deelnemerDao)
    {
        $this->dao = $dao;
        $this->daoDeelnemers = $deelnemerDao;
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

    protected function beforeDeleteCheck($entity): bool {
        $countDeelnemers = $this->daoDeelnemers->countByProjectId($entity->getId());
        if ($countDeelnemers > 0) {
            throw new \Exception(
                "Dit project ({$entity->getNaam()}) kan niet worden verwijderd, omdat er al deelnemers ({$countDeelnemers}) aan gekoppeld zijn." .
                " Verwijder eerst de deelnemers voordat je het project verwijdert."
            );
        }
        return true;
    }

    protected function checkSoftDelete($entity): bool {
        return false;
    }
}
