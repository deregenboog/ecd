<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\ExportInterface;
use ClipBundle\Entity\Client;
use ClipBundle\Form\ClientFilterType;
use ClipBundle\Form\ClientType;
use ClipBundle\Service\ClientDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/clienten")
 * @Template
 */
class ClientenController extends AbstractController
{
    protected $title = 'Cliënten';
    protected $entityName = 'cliënt';
    protected $entityClass = Client::class;
    protected $formClass = ClientType::class;
    protected $filterFormClass = ClientFilterType::class;
    protected $baseRouteName = 'clip_clienten_';

    /**
     * @var ClientDaoInterface
     *
     * @DI\Inject("clip.dao.client")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("clip.export.clienten")
     */
    protected $export;
}
