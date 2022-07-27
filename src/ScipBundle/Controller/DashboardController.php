<?php

namespace ScipBundle\Controller;

use AppBundle\Controller\SymfonyController;
use JMS\DiExtraBundle\Annotation as DI;
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
     *
     * @DI\Inject("ScipBundle\Service\ProjectDao")
     */
    protected $projectDao;

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
            $this->addFlash("info","SCIP is gemigreerd naar dagbestding. Het menu-item verdwijnt binnenkort.");
            return $this->redirectToRoute("dagbesteding_dashboard_index");

            $pagination = $this->projectDao->setItemsPerPage(100)->findByMedewerker($this->getMedewerker(), $page);
        }

        return [
            'projecten' => $pagination,
        ];
    }
}
