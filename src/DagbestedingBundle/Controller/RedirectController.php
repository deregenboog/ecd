<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\SymfonyController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route
 */
class RedirectController extends SymfonyController
{
    /**
     * @Route(name="dagbesteding_index")
     */
    public function indexAction()
    {
        if (!$this->isGranted('ROLE_DAGBESTEDING')) {
            return $this->redirectToRoute('dagbesteding_trajecten_index');
        }

        return $this->redirectToRoute('dagbesteding_dashboard_index');
    }
}
