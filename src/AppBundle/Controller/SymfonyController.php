<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class SymfonyController extends AbstractController
{
    /**
     * @var string
     */
    protected $module;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $subnavigation;

    /**
     * @var string
     */
    protected $entityName;

    /**
     * @var string
     */
    protected $baseRouteName;

    /** @var PaginatorInterface */
    protected $paginator;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;


    public function __construct(PaginatorInterface $paginator, EntityManagerInterface $entityManager)
    {
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getSubnavigation()
    {
        if ($this->subnavigation) {
            return $this->subnavigation;
        }

        $matches = [];
        if (preg_match('/^(.*)Bundle/', get_class($this), $matches)) {
            return sprintf('@%s/subnavigation.html.twig', $matches[1]);
        }
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

    protected function getForm($type, $data = null, array $options = [])
    {
        return $this->createForm($type, $data, $options);
    }

    /**
     * @return Medewerker
     */
    protected function getMedewerker()
    {
        return $this->getUser();
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return PaginatorInterface
     */
    protected function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * @var string
     *
     * @return LoggerInterface
     */
    protected function getLogger($channel = null)
    {
        if ($channel) {
            return $this->get('monolog.logger.'.$channel);
        }

        return $this->get('monolog.logger');
    }
}
