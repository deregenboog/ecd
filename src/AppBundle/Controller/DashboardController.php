<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends SymfonyController
{
    /**
     * @Route("/", name="home")
     * @Template
     */
    public function indexAction(Request $request)
    {
        return [];
    }
}
