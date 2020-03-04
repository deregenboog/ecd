<?php

namespace ScipBundle\Controller;

use AppBundle\Controller\SymfonyController;
use ScipBundle\Service\ProjectDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/mijn")
 * @Template
 */
class DashboardController extends SymfonyController
{
    protected $title = 'Mijn SCIP';

    /**
     * @var ProjectDaoInterface
     */
    protected $projectDao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->projectDao = $container->get("ScipBundle\Service\ProjectDao");
    
        return $previous;
    }

    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('scip_dashboard_projecten');
    }

    /**
     * @Route("/projecten")
     */
    public function projectenAction(Request $request)
    {
        $page = $request->get('page', 1);

        if ($this->isGranted('ROLE_SCIP_BEHEER')) {
            $pagination = $this->projectDao->setItemsPerPage(100)->findAll($page);
        } else {
            $pagination = $this->projectDao->setItemsPerPage(100)->findByMedewerker($this->getMedewerker(), $page);
        }

        return [
            'projecten' => $pagination,
        ];
    }
}
