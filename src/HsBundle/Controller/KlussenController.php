<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use HsBundle\Entity\Klus;
use HsBundle\Form\KlusCloseType;
use HsBundle\Form\KlusFilterType;
use HsBundle\Form\KlusType;
use HsBundle\Service\KlusDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use HsBundle\Form\KlusCancelType;

/**
 * @Route("/klussen")
 * @Template
 */
class KlussenController extends AbstractChildController
{
    protected $title = 'Klussen';
    protected $entityName = 'klus';
    protected $entityClass = Klus::class;
    protected $formClass = KlusType::class;
    protected $filterFormClass = KlusFilterType::class;
    protected $addMethod = 'addKlus';
    protected $baseRouteName = 'hs_klussen_';

    /**
     * @var KlusDaoInterface
     *
     * @DI\Inject("hs.dao.klus")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("hs.export.klus")
     */
    protected $export;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("hs.klus.entities")
     */
    protected $entities;

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $this->formClass = KlusCloseType::class;

        return $this->editAction($request, $id);
    }

    /**
     * @Route("/{id}/cancel")
     */
    public function annulerenAction(Request $request, $id)
    {
        $this->formClass = KlusCancelType::class;

        return $this->editAction($request, $id);
    }
}
