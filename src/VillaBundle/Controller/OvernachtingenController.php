<?php

namespace VillaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\AbstractController;
use AppBundle\Controller\DisableIndexActionTrait;
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
class OvernachtingenController extends AbstractController
{
    use DisableIndexActionTrait;

    protected $entityName = 'Overnachting';
    protected $entityClass = Overnachting::class;
    protected $formClass = OvernachtingType::class;
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
     * @Route("/")
     * @param Request $request
     * @return void
     */
    public function indexAction(Request $request)
    {
        //faux. for testing, the default index route is disabled with the trait
        //for the calendar which is used, the index route should exist.
        return new Response();
    }

    /**
     * @Route("/create/{datum}/{slaper}")
     * @ParamConverter("slaper", class="VillaBundle\Entity\Slaper")
     */
    public function createOvernachtingAction(Request $request, ?string $datum = null, ?Slaper $slaper = null)
    {
        $overnachting = new Overnachting();

        // If $datum is not null, then we are creating a new entity. Convert date string to DateTime object and set it.
        if ($datum !== null && $slaper !== null) {
            $overnachting->setDatum(\DateTime::createFromFormat('Y-m-d', $datum));
            $overnachting->setSlaper($slaper);
        }

        $form = $this->getForm(OvernachtingType::class, $overnachting, ['method' => $request->getMethod()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($overnachting);
            $this->entityManager->flush();
            return new JsonResponse(['success' => true, 'message' => 'Entity created successfully'], Response::HTTP_OK);
        }
        $formHtml = $this->renderView('edit.ajax.html.twig', ['form' => $form->createView()]);
        return new JsonResponse(['formHtml' => $formHtml, 'success' => false]);
    }

    /**
     * @Route("/edit/{id}/")
     */
    public function editOvernachtingAction(Request $request, Overnachting $overnachting)
    {
        $form = $this->getForm(OvernachtingType::class, $overnachting, ['method' => $request->getMethod()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->flush();
            return new JsonResponse(['success' => true, 'message' => 'Entity updated successfully'], Response::HTTP_OK);
        }

        $formHtml = $this->renderView('edit.ajax.html.twig', ['form' => $form->createView()]);
        return new JsonResponse(['formHtml' => $formHtml, 'success' => true ]);
    }
    /**
     * @Route("/delete/{id}")
     */
    public function deleteOvernachtingAction(Request $request, int $id): Response
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
