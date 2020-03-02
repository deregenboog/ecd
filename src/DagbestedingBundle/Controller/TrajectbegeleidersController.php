<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Trajectbegeleider;
use DagbestedingBundle\Form\TrajectbegeleiderType;
use DagbestedingBundle\Service\TrajectbegeleiderDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/trajectbegeleiders")
 */
class TrajectbegeleidersController extends AbstractController
{
    protected $title = 'Trajectbegeleiders';
    protected $entityName = 'Trajectbegeleider';
    protected $entityClass = Trajectbegeleider::class;
    protected $formClass = TrajectbegeleiderType::class;
    protected $baseRouteName = 'dagbesteding_trajectbegeleiders_';

    /**
     * @var TrajectbegeleiderDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        parent::setContainer($container);

        $this->dao = $this->get("DagbestedingBundle\Service\TrajectbegeleiderDao");
    
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
