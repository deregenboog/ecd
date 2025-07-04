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

    /**
     * @Route("/{id}/delete")
     *
     * @Template
     *
     * @var Request
     * @var int
     */
    public function deleteAction(Request $request, $id)
    {
        $project = $this->dao->find($id);
        $projectName = $project->getNaam();
        $countDeelnemers = $this->daoDeelnemers->countByProjectId($project->getId());

        if ($countDeelnemers > 0) {
            $this->addFlash(
                'danger',
                "Dit project ($projectName) kan niet worden verwijderd, omdat er al deelnemers ($countDeelnemers) aan gekoppeld zijn." .
                    " Verwijder eerst de deelnemers voordat je het project verwijdert."
            );
            return $this->redirectToIndex();
        } else {

            $project = $this->dao->find($id);

            $form = $this->getForm(ConfirmationType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->get('yes')->isClicked()) {
                    $this->beforeDelete($project);
                    $this->dao->delete($project);
                    $this->afterDelete($project);
                    $this->addFlash('success', ucfirst($this->entityName) . ' is verwijderd. ('.$projectName.')');
                    return $this->redirectToIndex();
                } else {
                    return $this->redirectToView($project);
                }
            }

            return [
                'entity' => $project,
                'form' => $form->createView(),
            ];
        }
    }
}
