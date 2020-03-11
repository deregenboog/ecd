<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Behandelaar;
use ClipBundle\Form\BehandelaarType;
use ClipBundle\Service\BehandelaarDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/behandelaars")
 * @Template
 */
class BehandelaarsController extends AbstractController
{
    protected $title = 'Medewerkers';
    protected $entityName = 'medewerker';
    protected $entityClass = Behandelaar::class;
    protected $formClass = BehandelaarType::class;
    protected $baseRouteName = 'clip_behandelaars_';

    /**
     * @var BehandelaarDaoInterface
     */
    protected $dao;

    public function setContainer(?\Symfony\Component\DependencyInjection\ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->dao = $container->get("ClipBundle\Service\BehandelaarDao");
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
