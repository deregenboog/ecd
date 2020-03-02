<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Trajectsoort;
use DagbestedingBundle\Form\TrajectsoortType;
use DagbestedingBundle\Service\TrajectsoortDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/trajectsoorten")
 */
class TrajectsoortenController extends AbstractController
{
    protected $title = 'Soorten trajecten';
    protected $entityName = 'Soort traject';
    protected $entityClass = Trajectsoort::class;
    protected $formClass = TrajectsoortType::class;
    protected $baseRouteName = 'dagbesteding_trajectsoorten_';

    /**
     * @var TrajectsoortDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        parent::setContainer($container);

        $this->dao = $this->get("DagbestedingBundle\Service\TrajectsoortDao");
    
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
