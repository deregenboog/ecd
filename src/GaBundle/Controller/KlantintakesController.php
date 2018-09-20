<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\KlantFilterType;
use AppBundle\Service\KlantDaoInterface;
use GaBundle\Entity\KlantIntake;
use GaBundle\Entity\KlantLidmaatschap;
use GaBundle\Entity\KlantVerslag;
use GaBundle\Form\AanmeldingType;
use GaBundle\Form\KlantIntakeFilterType;
use GaBundle\Form\KlantIntakeType;
use GaBundle\Service\KlantIntakeDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/klantintakes")
 */
class KlantintakesController extends AbstractController
{
    protected $title = 'Deelnemers';
    protected $entityName = 'deelnemer';
    protected $entityClass = KlantIntake::class;
    protected $formClass = KlantIntakeType::class;
    protected $filterFormClass = KlantIntakeFilterType::class;
    protected $baseRouteName = 'ga_klantintakes_';

    /**
     * @var KlantIntakeDaoInterface
     *
     * @DI\Inject("GaBundle\Service\KlantIntakeDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("ga.export.klantdossiers")
     */
    protected $export;

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("AppBundle\Service\KlantDao")
     */
    private $klantDao;

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        if ($request->get('klant')) {
            return $this->doAdd($request);
        }

        return $this->doSearch($request);
    }

    /**
     * @Route("/{id}/open")
     */
    public function openAction($id)
    {
        $intake = $this->intakeDao->find($id);

        $aanmelding = new Aanmelding();
        $intake->addAanmelding($aanmelding);

        $form = $this->createForm(AanmeldingType::class, $aanmelding);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->intakeDao->update($intake);
                $this->addFlash('success', 'Het dossier is heropend.');

                return $this->redirectToRoute('ga_klanten_view', ['id' => $intake->getId()]);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return [
            'form' => $form->createView(),
            'deelnemer' => $intake,
        ];
    }

    private function doSearch(Request $request)
    {
        $form = $this->createForm(KlantFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'bsn', 'geboortedatum'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $count = (int) $this->klantDao->countAll($form->getData());
            if (0 === $count) {
                $this->addFlash('info', sprintf('De zoekopdracht leverde geen resultaten op. Maak een nieuwe %s aan.', $this->entityName));

                return $this->redirectToRoute($this->baseRouteName.'add', ['klant' => 'new']);
            }

            if ($count > 100) {
                $form->addError(new FormError('De zoekopdracht leverde teveel resultaten op. Probeer het opnieuw met een specifiekere zoekopdracht.'));

                return [
                    'form' => $form->createView(),
                ];
            }

            return [
                'form' => $form->createView(),
                'klanten' => $this->klantDao->findAll(null, $form->getData()),
            ];
        }

        return [
            'form' => $form->createView(),
        ];
    }

    private function doAdd(Request $request)
    {
        $klantId = $request->get('klant');
        if ('new' === $klantId) {
            $klant = new Klant();
        } else {
            $klant = $this->klantDao->find($klantId);
        }

        // redirect if already exists
        $intake = $this->dao->findOneByKlant($klant);
        if ($intake) {
            return $this->redirectToView($intake);
        }

        $intake = new KlantIntake($klant);
        $form = $this->createForm($this->formClass, $intake);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($intake);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                return $this->redirectToView($intake);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'form' => $form->createView(),
        ];
    }

    protected function addParams($entity, Request $request)
    {
        $event = new DienstenLookupEvent($entity->getKlant()->getId());
        $this->get('event_dispatcher')->dispatch(Events::DIENSTEN_LOOKUP, $event);

        $lidmaatschappen = $this->getDoctrine()->getRepository(KlantLidmaatschap::class)
            ->findBy(['klant' => $entity->getKlant()], ['created' => 'desc']);

        $verslagen = $this->getDoctrine()->getRepository(KlantVerslag::class)
            ->findBy(['klant' => $entity->getKlant()], ['created' => 'desc']);

        return [
            'diensten' => $event->getDiensten(),
            'lidmaatschappen' => $lidmaatschappen,
            'verslagen' => $verslagen,
        ];
    }
}
