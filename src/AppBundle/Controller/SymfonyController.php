<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
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

    public function getTitle()
    {
        return $this->title;
    }

    public function getEntityName()
    {
        return $this->entityName;
    }

    public function getBaseRouteName()
    {
        return $this->baseRouteName;
    }

    public function getRequest()
    {
        return $this->container->get('request_stack')->getCurrentRequest();
    }

    /**
     * @return Medewerker
     */
    protected function getMedewerker()
    {
        return $this->getUser();
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @return PaginatorInterface
     */
    protected function getPaginator()
    {
        return $this->get('knp_paginator');
    }
}
