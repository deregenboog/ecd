<?php

namespace VillaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use VillaBundle\Entity\Document;
use VillaBundle\Entity\Overnachting;
use VillaBundle\Entity\Slaper;
use VillaBundle\Form\DocumentType;
use VillaBundle\Form\OvernachtingType;
use VillaBundle\Service\DocumentDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use VillaBundle\Service\OvernachtingDaoInterface;

/**
 * @Route("/overnachtingen")
 *
 */
class OvernachtingenController extends AbstractChildController
{
    protected $entityName = 'Overnachting';
    protected $entityClass = Overnachting::class;
    protected $formClass = OvernachtingType::class;
    protected $addMethod = 'addOvernachting';
    protected $allowEmpty = true;
    protected $baseRouteName = 'villa_overnachtingen_';

    /**
     * @var OvernachtingDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(OvernachtingDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }

    /**
     * @Route("/ajax/form/{id}", defaults={"id"=null}, methods={"GET"})
     */
    public function ajaxFormAction(Request $request, ?int $id = null): Response
    {
        $entity = $id ? $this->dao->find($id) : new $this->entityClass();
        if (!$entity) {
            return new JsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        // Fetch datum from the request and set it to the entity. HandleRequest only works if the form is submitted, which is not in the first place.
        $datumInRequest = $request->query->get('overnachting')['datum'] ?? null;
        if ($datumInRequest) {
            $datumDateTime = \DateTime::createFromFormat('Y-m-d', $datumInRequest);
            if ($datumDateTime !== false) {
                $entity->setDatum($datumDateTime);
            }
        }

        $form = $this->getForm($this->formClass, $entity, $this->formOptions);

        $formHtml = $this->renderView('edit.ajax.html.twig', [
            'form' => $form->createView(),
        ]);

        return new JsonResponse(['formHtml' => $formHtml]);
    }

    /**
     * @Route("/ajax/submit/{slaper}" )
     * @ParamConverter("slaper", class="VillaBundle\Entity\Slaper")
     */
    public function ajaxSubmitAction(Request $request, Slaper $slaper, ?int $id = null): Response
    {
        $request->setRequestFormat('json');
        $entity = $id ? $this->dao->find($id) : new $this->entityClass();
        if (!$entity && $id) {
            return new JsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->getForm($this->formClass, $entity, array_merge($this->formOptions, ['method' => 'POST']));
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entity->setSlaper($slaper);
            if ($form->isValid()) {
                try {
                    $this->entityManager->persist($entity);
                    $this->entityManager->flush();

                    return new JsonResponse(['success' => true, 'message' => 'Entity saved successfully']);
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                }

            } else {
                foreach ($form->getErrors(true, true) as $error) {
                $errors[] = [
                    'name' => $error->getOrigin()->getName(),
                    'message' => $error->getMessage()
                ];
            }
        }
    }
        // Form not valid, return the form errors
        $errors = []; // Implement logic to extract form errors
        return new JsonResponse(['success' => false, 'errors' => $errors], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/ajax/delete/{id}", methods={"POST"})
     */
    public function ajaxDeleteAction(Request $request, int $id): Response
    {
        $entity = $this->dao->find($id);
        if (!$entity) {
            return new JsonResponse(['error' => 'Entity not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->beforeDelete($entity);
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
            $this->afterDelete($entity);

            return new JsonResponse(['success' => true, 'message' => 'Entity deleted successfully']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Could not delete entity'], Response::HTTP_BAD_REQUEST);
        }
    }

}
