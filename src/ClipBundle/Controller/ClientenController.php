<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\ExportInterface;
use ClipBundle\Entity\Client;
use ClipBundle\Form\ClientFilterType;
use ClipBundle\Form\ClientType;
use ClipBundle\Service\ClientDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ClipBundle\Service\ClientDao;

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
     */
    protected $dao;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("ClipBundle\Service\ClientDao");
        $this->export = $container->get("clip.export.clienten");
    
        return $previous;
    }
}
