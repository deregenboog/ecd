<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Resultaatgebiedsoort;
use DagbestedingBundle\Form\ResultaatgebiedsoortType;
use DagbestedingBundle\Service\ResultaatgebiedsoortDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/resultaatgebiedsoorten")
 */
class ResultaatgebiedsoortenController extends AbstractController
{
    protected $title = 'Soorten resultaatgebieden';
    protected $entityName = 'Soort resultaatgebied';
    protected $entityClass = Resultaatgebiedsoort::class;
    protected $formClass = ResultaatgebiedsoortType::class;
    protected $baseRouteName = 'dagbesteding_resultaatgebiedsoorten_';

    /**
     * @var ResultaatgebiedsoortDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("DagbestedingBundle\Service\ResultaatgebiedsoortDao");
    
        return $previous;
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
