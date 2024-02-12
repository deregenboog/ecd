<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\ExportInterface;
use HsBundle\Entity\Klus;
use HsBundle\Form\KlusCancelType;
use HsBundle\Form\KlusCloseType;
use HsBundle\Form\KlusFilterType;
use HsBundle\Form\KlusType;
use HsBundle\Service\KlusDao;
use HsBundle\Service\KlusDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/klussen")
 * @Template
 */
class KlussenController extends AbstractChildController
{
    protected $entityName = 'klus';
    protected $entityClass = Klus::class;
    protected $formClass = KlusType::class;
    protected $filterFormClass = KlusFilterType::class;
    protected $addMethod = 'addKlus';
    protected $baseRouteName = 'hs_klussen_';

    /**
     * @var KlusDao
     */
    protected $dao;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @param KlusDao $dao
     * @param ExportInterface $export
     * @param \ArrayObject $entities
     */
    public function __construct(KlusDao $dao, ExportInterface $export, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->export = $export;
        $this->entities = $entities;
    }


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

    /**
     * @Route("/{id}/reopen")
     */
    public function heropenenAction(Request $request, $id)
    {


        $entity = $this->dao->find($id);;
        $entity->setAnnuleringsdatum(null);
        $this->dao->update($entity);
        $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

        return $this->redirectToView($entity);

    }
}
