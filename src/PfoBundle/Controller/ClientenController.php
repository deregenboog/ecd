<?php

namespace PfoBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use PfoBundle\Entity\Client;
use PfoBundle\Form\ClientFilterType;
use PfoBundle\Form\ClientType;
use PfoBundle\Service\ClientDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Export\ExportInterface;
use PfoBundle\Form\ClientConnectType;

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
