<?php

namespace ScipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ScipBundle\Entity\Project;
use ScipBundle\Form\ProjectFilterType;
use ScipBundle\Form\ProjectType;
use ScipBundle\Security\Permissions;
use ScipBundle\Service\ProjectDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/projecten")
 * @Template
 */
class ProjectenController extends AbstractController
{
    protected $title = 'Projecten';
    protected $entityName = 'project';
    protected $entityClass = Project::class;
    protected $formClass = ProjectType::class;
    protected $filterFormClass = ProjectFilterType::class;
    protected $baseRouteName = 'scip_projecten_';
    protected $disabledActions = [];

    /**
     * @var ProjectDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("ScipBundle\Service\ProjectDao");
    
        return $previous;
    }

    /**
     * @Route("/")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $form = $this->getForm($this->filterFormClass);
        $form->handleRequest($request);
        $filter = $form->getData();

        $page = $request->get('page', 1);
        if ($this->isGranted('ROLE_SCIP_BEHEER')) {
            $pagination = $this->dao->findAll($page, $filter);
        } else {
            $pagination = $this->dao->findByMedewerker($this->getMedewerker(), $page, $filter);
        }

        return [
            'filter' => $form->createView(),
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/{id}/view")
     * @Template
     */
    public function viewAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $this->denyAccessUnlessGranted(Permissions::ACCESS, $entity);

        return parent::viewAction($request, $id);
    }

    /**
     * @Route("/{id}/edit")
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $this->denyAccessUnlessGranted(Permissions::ACCESS, $entity);

        return parent::editAction($request, $id);
    }

    /**
     * @Route("/{id}/delete")
     * @Template
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $this->denyAccessUnlessGranted(Permissions::ACCESS, $entity);

        return parent::deleteAction($request, $id);
    }
}
