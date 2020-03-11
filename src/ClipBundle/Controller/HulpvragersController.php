<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Hulpvrager;
use ClipBundle\Form\HulpvragerType;
use ClipBundle\Service\HulpvragerDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/hulpvragers")
 * @Template
 */
class HulpvragersController extends AbstractController
{
    protected $title = 'Hulpvragers';
    protected $entityName = 'hulpvrager';
    protected $entityClass = Hulpvrager::class;
    protected $formClass = HulpvragerType::class;
    protected $baseRouteName = 'clip_hulpvragers_';

    /**
     * @var HulpvragerDaoInterface
     */
    protected $dao;

    public function setContainer(?\Symfony\Component\DependencyInjection\ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->dao = $container->get("ClipBundle\Service\HulpvragerDao");
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
