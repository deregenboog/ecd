<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Klus;
use HsBundle\Form\KlusType;
use Symfony\Component\Routing\Annotation\Route;
use HsBundle\Service\KlusDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use HsBundle\Form\KlusFilterType;
use AppBundle\Controller\AbstractChildController;

/**
 * @Route("/klussen")
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
}
