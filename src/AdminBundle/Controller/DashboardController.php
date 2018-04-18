<?php

namespace AdminBundle\Controller;

use AppBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/")
 */
class DashboardController extends AbstractController
{
    /**
     * @var string
     */
    protected $title = 'Dashboard';

    /**
     * @Route("/")
     * @Template
     */
    public function indexAction(Request $request)
    {
        return [];
    }
}
