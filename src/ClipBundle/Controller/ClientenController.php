<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\ExportInterface;
use ClipBundle\Entity\Client;
use ClipBundle\Form\ClientFilterType;
use ClipBundle\Form\ClientType;
use ClipBundle\Service\ClientDao;
use ClipBundle\Service\ClientDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
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
     * @var ClientDao
     */
    protected $dao;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @param ClientDao $dao
     * @param ExportInterface $export
     */
    public function __construct(ClientDao $dao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->export = $export;
    }


}
