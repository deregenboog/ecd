<?php

namespace DagbestedingBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Controller\SymfonyController;
use AppBundle\Form\ConfirmationType;
use DagbestedingBundle\Form\AfsluitingType;
use DagbestedingBundle\Service\AfsluitingDaoInterface;

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
        if (!in_array(GROUP_TRAJECTBEGELEIDER, $this->userGroups)) {
            return $this->redirectToRoute('dagbesteding_trajecten_index');
        }

        return $this->redirectToRoute('dagbesteding_deelnemers_index');
    }
}
