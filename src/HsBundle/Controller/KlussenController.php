<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\ExportInterface;
use HsBundle\Entity\Klus;
use HsBundle\Form\KlusCancelType;
use HsBundle\Form\KlusCloseType;
use HsBundle\Form\KlusFilterType;
use HsBundle\Form\KlusType;
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
    protected $title = 'Klussen';
    protected $entityName = 'klus';
    protected $entityClass = Klus::class;
    protected $formClass = KlusType::class;
    protected $filterFormClass = KlusFilterType::class;
    protected $addMethod = 'addKlus';
    protected $baseRouteName = 'hs_klussen_';

    /**
     * @var KlusDaoInterface
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

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("HsBundle\Service\KlusDao");
        $this->export = $container->get("hs.export.klus");
        $this->entities = $container->get("hs.klus.entities");
    
        return $previous;
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
