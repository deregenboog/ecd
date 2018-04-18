<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class SymfonyController extends Controller
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var string
     */
    protected $baseRouteName;

    public $uses = [];

    public function getTitle()
    {
        return $this->title;
    }

    public function getEntityName()
    {
        return $this->entityName;
    }

    public function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    public function getBaseRouteName()
    {
        return $this->baseRouteName;
    }

    protected function getMedewerker()
    {
        return $this->getUser();
    }

    protected function getRequest()
    {
        return $this->container->get('request_stack')->getCurrentRequest();
    }
}
