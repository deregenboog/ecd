<?php

namespace ClipBundle\Controller;

use AppBundle\Export\ExportInterface;
use AppBundle\Form\ConfirmationType;
use ClipBundle\Entity\Vraag;
use ClipBundle\Form\VraagCloseType;
use ClipBundle\Form\VraagType;
use ClipBundle\Form\VragenModel;
use ClipBundle\Form\VragenType;
use ClipBundle\Service\VraagDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/vragen")
 * @Template
 */
class VragenController extends AbstractVragenController
{
    protected $title = 'Vragen';
    protected $formClass = VraagType::class;
    protected $addMethod = 'addVraag';

    /**
     * @var VraagDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        parent::setContainer($container);

        $this->dao = $this->get("ClipBundle\Service\VraagDao");
        $this->entities = $this->get("clip.vraag.entities");
        $this->export = $this->get("clip.export.vragen");
    
        return $container;
    }

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $this->formClass = VragenType::class;

        list($parentEntity, $this->parentDao) = $this->getParentConfig($request);
        $entity = new VragenModel($parentEntity);
        $entity->setClient($parentEntity);

        $form = $this->getForm($this->formClass, $entity, [
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

        $form = $this->getForm(VraagCloseType::class, $entity);
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

        $form = $this->getForm(ConfirmationType::class)->handleRequest($request);

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

    /**
     * @Route("/{id}/vraagHulp", methods={"POST"})
     */
    public function vraagHulpAction(Request $request, Vraag $vraag)
    {

        $vraag->setHulpCollegaGezocht(!$vraag->isHulpCollegaGezocht());
        $this->dao->update($vraag);

        return new JsonResponse(['hulpGezocht' => $vraag->isHulpCollegaGezocht()]);
    }


    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        if (!$this->isGranted('ROLE_CLIP_BEHEER')) {
            return $this->redirectToView($entity);
        }

        return parent::deleteAction($request, $id);
    }
}
