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
        if (!array_key_exists(GROUP_DAGBESTEDING, $this->userGroups)) {
            return $this->redirectToRoute('dagbesteding_trajecten_index');
        }

        return $this->redirectToRoute('dagbesteding_deelnemers_index');
    }
}
