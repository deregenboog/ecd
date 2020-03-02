<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Hulpvrager;
use ClipBundle\Form\HulpvragerType;
use ClipBundle\Service\HulpvragerDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        parent::setContainer($container);

        $this->dao = $this->get("ClipBundle\Service\HulpvragerDao");
    
        return $container;
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
