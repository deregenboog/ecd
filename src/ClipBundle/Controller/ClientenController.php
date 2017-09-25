<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Form\KlantFilterType;
use ClipBundle\Entity\Client;
use ClipBundle\Form\ClientCloseType;
use ClipBundle\Form\ClientFilterType;
use ClipBundle\Form\ClientSelectType;
use ClipBundle\Form\ClientType;
use ClipBundle\Service\ClientDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Export\ExportInterface;

/**
 * @Route("/clienten")
 */
class ClientenController extends AbstractController
{
    protected $title = 'Cliënten';
    protected $entityName = 'cliënt';
    protected $entityClass = Client::class;
    protected $formClass = ClientType::class;
    protected $filterFormClass = ClientFilterType::class;
    protected $baseRouteName = 'clip_clienten_';

    /**
     * @var ClientDaoInterface
     *
     * @DI\Inject("clip.dao.client")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("clip.export.clienten")
     */
    protected $export;

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        if ($request->query->has('klant')) {
            $klant = new Klant();
            if ($request->query->get('klant') !== 'new') {
                $klant = $this->getEntityManager()->find(Klant::class, $request->query->get('klant'));
            }

            $entity = new Client();
            $entity->setKlant($klant);

            $creationForm = $this->createForm(ClientType::class, $entity);
            $creationForm->handleRequest($request);

            if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                try {
                    $this->dao->create($entity);

                    $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                    return $this->redirectToView($entity);
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Er is een fout opgetreden.');

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

        $selectionForm = $this->createForm(ClientSelectType::class, null, [
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

            return $this->redirectToRoute($this->baseRouteName.'add', ['klant' => $id]);
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

        $form = $this->createForm(ClientCloseType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);

                $this->addFlash('success', $this->entityName.' is afgesloten.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToView($entity);
        }

        return [
            'client' => $entity,
            'form' => $form->createView(),
        ];
    }
}
