<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantFilterType;
use DagbestedingBundle\Entity\Deelnemer;
use DagbestedingBundle\Form\DeelnemerCloseType;
use DagbestedingBundle\Form\DeelnemerFilterType;
use DagbestedingBundle\Form\DeelnemerSelectType;
use DagbestedingBundle\Form\DeelnemerType;
use DagbestedingBundle\Service\DeelnemerDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Export\GenericExport;

/**
 * @Route("/deelnemers")
 */
class DeelnemersController extends AbstractController
{
    protected $title = 'Deelnemers';
    protected $entityName = 'Deelnemer';
    protected $entityClass = Deelnemer::class;
    protected $formClass = DeelnemerType::class;
    protected $filterFormClass = DeelnemerFilterType::class;
    protected $baseRouteName = 'dagbesteding_deelnemers_';

    /**
     * @var DeelnemerDaoInterface
     *
     * @DI\Inject("dagbesteding.dao.deelnemer")
     */
    protected $dao;

    /**
     * @var GenericExport
     *
     * @DI\Inject("dagbesteding.export.deelnemers")
     */
    protected $export;

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        if ($request->query->has('klantId')) {
            $klant = new Klant();
            if ('new' !== $request->query->get('klantId')) {
                $klant = $this->getEntityManager()->find(Klant::class, $request->query->get('klantId'));
            }

            $entity = new Deelnemer();
            $entity->setKlant($klant);

            $creationForm = $this->createForm(DeelnemerType::class, $entity);
            $creationForm->handleRequest($request);

            if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                try {
                    $this->dao->create($entity);

                    $this->addFlash('success', $this->entityName.' is opgeslagen.');

                    return $this->redirectToView($entity);
                } catch (\Exception $e) {
                    $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                    $this->addFlash('danger', $message);

                    return $this->redirectToIndex();
                }
            }

            return [
                'creationForm' => $creationForm->createView(),
            ];
        }

        $filterForm = $this->createForm(KlantFilterType::class, null, [
            'enabled_filters' => ['naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($request);

        $selectionForm = $this->createForm(DeelnemerSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            return ['selectionForm' => $selectionForm->createView()];
        }

        if ($selectionForm->isSubmitted() && $selectionForm->isValid()) {
            $entity = $selectionForm->getData();
            if ($entity->getKlant() instanceof Klant) {
                $id = $entity->getKlant()->getId();
            } else {
                $id = 'new';
            }

            return $this->redirectToRoute($this->baseRouteName.'add', ['klantId' => $id]);
        }

        return [
            'filterForm' => $filterForm->createView(),
        ];
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(DeelnemerCloseType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);

                $this->addFlash('success', $this->entityName.' is afgesloten.');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToView($entity);
        }

        return [
            'deelnemer' => $entity,
            'form' => $form->createView(),
        ];
    }
}
