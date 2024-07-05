<?php

namespace PfoBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\ExportInterface;
use PfoBundle\Entity\Client;
use PfoBundle\Form\ClientConnectType;
use PfoBundle\Form\ClientFilterType;
use PfoBundle\Form\ClientType;
use PfoBundle\Service\ClientDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/clienten")
 *
 * @Template
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
     */
    protected $dao;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct(ClientDaoInterface $dao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->export = $export;
    }

    /**
     * @Route("/{id}/connect")
     */
    public function connectAction(Request $request, $id)
    {
        $this->formClass = ClientConnectType::class;
        $entity = $this->dao->find($id);

        return $this->processForm($request, $entity);
    }
}
