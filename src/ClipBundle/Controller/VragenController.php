<?php

namespace ClipBundle\Controller;

use ClipBundle\Service\VraagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use ClipBundle\Entity\Vraag;
use ClipBundle\Form\VraagType;
use ClipBundle\Form\VraagCloseType;
use AppBundle\Export\ExportInterface;
use ClipBundle\Form\VragenType;
use ClipBundle\Form\VragenModel;
use AppBundle\Form\ConfirmationType;

/**
 * @Route("/vragen")
 */
class VragenController extends AbstractVragenController
{
    protected $title = 'Vragen';
    protected $formClass = VraagType::class;
    protected $addMethod = 'addVraag';

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
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $this->formClass = VragenType::class;

        list($parentEntity, $this->parentDao) = $this->getParentConfig($request);
        $entity = new VragenModel($parentEntity);
        $entity->setClient($parentEntity);

        $form = $this->createForm($this->formClass, $entity, [
            'medewerker' => $this->getMedewerker(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                foreach ($entity->getVragen() as $vraag) {
                    $parentEntity->addVraag($vraag);
                }
                $this->parentDao->update($parentEntity);
                $this->addFlash('success', ucfirst($this->entityName).' is toegevoegd.');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            if ($parentEntity) {
                return $this->redirectToView($parentEntity);
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'parent_entity' => $parentEntity,
            'form' => $form->createView(),
        ];
    }

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
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/reopen")
     */
    public function reopenAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(ConfirmationType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $this->generateUrl($this->baseRouteName.'view', ['id' => $entity->getId()]);
            if ($form->get('yes')->isClicked()) {
                $entity->heropen();
                $this->dao->update($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is heropend.');
            }

            return $this->redirect($url);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }
}
