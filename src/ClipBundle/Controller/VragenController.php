<?php

namespace ClipBundle\Controller;

use ClipBundle\Service\VraagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Export\GenericExport;
use ClipBundle\Form\VraagFilterType;
use AppBundle\Controller\AbstractChildController;
use ClipBundle\Entity\Vraag;
use ClipBundle\Form\VraagType;
use ClipBundle\Form\VraagCloseType;
use AppBundle\Export\ExportInterface;

/**
 * @Route("/vragen")
 */
class VragenController extends AbstractChildController
{
    protected $title = 'Vragen';
    protected $entityName = 'vraag';
    protected $entityClass = Vraag::class;
    protected $formClass = VraagType::class;
    protected $filterFormClass = VraagFilterType::class;
    protected $addMethod = 'addVraag';
    protected $baseRouteName = 'clip_vragen_';

    /**
     * @var VraagDaoInterface
     *
     * @DI\Inject("clip.dao.vraag")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("clip.vraag.entities")
     */
    protected $entities;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("clip.export.vragen")
     */
    protected $export;

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        $entity->setAfsluitdatum(new \DateTime());

        $form = $this->createForm(VraagCloseType::class, $entity);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is afgesloten.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }
}
