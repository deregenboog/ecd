<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Betaling;
use HsBundle\Form\BetalingFilterType;
use HsBundle\Form\BetalingType;
use Symfony\Component\Routing\Annotation\Route;
use HsBundle\Service\BetalingDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\ExportInterface;

/**
 * @Route("/betalingen")
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
     * @DI\Inject("hs.dao.betaling")
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
    public function viewAction($id)
    {
        return $this->redirectToIndex();
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }

    private function getFilter()
    {
        return $this->createForm(BetalingFilterType::class);
    }
}
