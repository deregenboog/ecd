<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\SymfonyController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route
 */
class RedirectController extends SymfonyController
{
    /**
     * @Route(name="inloop_index")
     */
    public function indexAction()
    {
        //to direct certain roles to their landing page.
        if ($this->isGranted('ROLE_INLOOP_VRIJWILLIGERS') && !$this->isGranted("ROLE_INLOOP_REGISTRATIES")) {
            return $this->redirectToRoute('inloop_vrijwilligers_index');
        }

        return $this->redirectToRoute('inloop_registraties_locationselect',['Inloop']);
    }
}
