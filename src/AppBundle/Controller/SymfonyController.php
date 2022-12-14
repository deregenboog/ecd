<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Medewerker;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class SymfonyController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
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

    /** @var PaginatorInterface */
    protected $paginator;

    /**
     * @param PaginatorInterface $paginator
     */
    public function __construct(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
    }


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

        return $this->logger;
    }
}
