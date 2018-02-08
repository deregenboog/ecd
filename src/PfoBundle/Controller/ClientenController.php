<?php

namespace PfoBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use JMS\DiExtraBundle\Annotation as DI;
use AppBundle\Controller\AbstractController;
use PfoBundle\Form\ClientFilterType;
use PfoBundle\Entity\Client;
use PfoBundle\Service\ClientDaoInterface;
use PfoBundle\Form\ClientType;

/**
 * @Route("/clienten")
 */
class ClientenController extends AbstractController
{
    protected $title = 'Cliënten';
    protected $entityName = 'client';
    protected $entityClass = Client::class;
    protected $formClass = ClientType::class;
    protected $filterFormClass = ClientFilterType::class;
    protected $baseRouteName = 'pfo_clienten_';
    protected $disabledActions = ['delete'];

    /**
     * @var ClientDaoInterface
     *
     * @DI\Inject("PfoBundle\Service\ClientDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("pfo.export.client")
     */
    protected $export;
}
