<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\ExportInterface;
use HsBundle\Entity\Betaling;
use HsBundle\Form\BetalingFilterType;
use HsBundle\Form\BetalingType;
use HsBundle\Service\BetalingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/betalingen")
 * @Template
 */
class BetalingenController extends AbstractChildController
{
    protected $title = 'Betalingen';
    protected $entityName = 'betaling';
    protected $entityClass = Betaling::class;
    protected $formClass = BetalingType::class;
    protected $filterFormClass = BetalingFilterType::class;
    protected $addMethod = 'addBetaling';
    protected $baseRouteName = 'hs_betalingen_';

    /**
     * @var BetalingDaoInterface
     *
     * @DI\Inject("HsBundle\Service\BetalingDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("hs.betaling.entities")
     */
    protected $entities;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("hs.export.betaling")
     */
    protected $export;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
