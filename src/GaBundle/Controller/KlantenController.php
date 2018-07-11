<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Export\ExportInterface;
use AppBundle\Service\KlantDaoInterface;
use GaBundle\Entity\KlantDeelname;
use GaBundle\Entity\KlantLidmaatschap;
use GaBundle\Entity\KlantVerslag;
use GaBundle\Form\AanmeldingType;
use GaBundle\Form\IntakeCloseType;
use GaBundle\Form\KlantIntakeType;
use GaBundle\Service\KlantIntakeDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/klanten")
 */
class KlantenController extends AbstractController
{
    protected $title = 'Deelnemers';
    protected $entityName = 'deelnemer';
    protected $entityClass = Klant::class;
    protected $formClass = KlantIntakeType::class;
    protected $baseRouteName = 'ga_klanten_';

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("AppBundle\Service\KlantDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("ga.export.klantdossiers")
     */
    protected $export;

    /**
     * @var KlantIntakeDaoInterface
     *
     * @DI\Inject("GaBundle\Service\KlantIntakeDao")
     */
    private $intakeDao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);

        $intake = $this->intakeDao->findOneByKlant($klant);

        return $this->redirectToRoute('ga_klantintakes_view', ['id' => $intake->getId()]);
    }

    /**
     * @Route("/{id}/view/verslagen")
     */
    public function viewVerslagenAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);

        $event = new DienstenLookupEvent($klant->getId());
        $this->get('event_dispatcher')->dispatch(Events::DIENSTEN_LOOKUP, $event);

        $intake = $this->intakeDao->findOneByKlant($klant);

        $verslagen = $this->getDoctrine()->getRepository(KlantVerslag::class)
            ->findBy(['klant' => $klant], ['created' => 'desc']);

        return [
            'entity' => $klant,
            'diensten' => $event->getDiensten(),
            'intake' => $intake,
            'verslagen' => $verslagen,
        ];
    }

    /**
     * @Route("/{id}/view/groepen")
     */
    public function viewGroepenAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);

        $event = new DienstenLookupEvent($klant->getId());
        $this->get('event_dispatcher')->dispatch(Events::DIENSTEN_LOOKUP, $event);

        $intake = $this->intakeDao->findOneByKlant($klant);

        $lidmaatschappen = $this->getDoctrine()->getRepository(KlantLidmaatschap::class)
            ->findBy(['klant' => $klant], ['created' => 'desc']);

        return [
            'entity' => $klant,
            'diensten' => $event->getDiensten(),
            'intake' => $intake,
            'lidmaatschappen' => $lidmaatschappen,
        ];
    }

    /**
     * @Route("/{id}/view/activiteiten")
     */
    public function viewActiviteitenAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);

        $event = new DienstenLookupEvent($klant->getId());
        $this->get('event_dispatcher')->dispatch(Events::DIENSTEN_LOOKUP, $event);

        $intake = $this->intakeDao->findOneByKlant($klant);

        $deelnames = $this->getDoctrine()->getRepository(KlantDeelname::class)
            ->findBy(['klant' => $klant], ['created' => 'desc']);

        return [
            'entity' => $klant,
            'diensten' => $event->getDiensten(),
            'intake' => $intake,
            'deelnames' => $deelnames,
        ];
    }

    /**
     * @Route("/{id}/view/afsluiting")
     */
    public function viewAfsluitingAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);

        $event = new DienstenLookupEvent($klant->getId());
        $this->get('event_dispatcher')->dispatch(Events::DIENSTEN_LOOKUP, $event);

        $intake = $this->intakeDao->findOneByKlant($klant);

        $lidmaatschappen = $this->getDoctrine()->getRepository(KlantLidmaatschap::class)
            ->findBy(['klant' => $klant, 'einddatum' => null]);

        return [
            'entity' => $klant,
            'diensten' => $event->getDiensten(),
            'intake' => $intake,
            'lidmaatschappen' => $lidmaatschappen,
        ];
    }

    /**
     * @Route("/{id}/open")
     */
    public function openAction($id)
    {
        $dossier = $this->intakeDao->find($id);

        $aanmelding = new Aanmelding();
        $dossier->addAanmelding($aanmelding);

        $form = $this->createForm(AanmeldingType::class, $aanmelding);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->intakeDao->update($dossier);
                $this->addFlash('success', 'Het dossier is heropend.');

                return $this->redirectToRoute('ga_klanten_view', ['id' => $dossier->getId()]);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return [
            'form' => $form->createView(),
            'deelnemer' => $dossier,
        ];
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);
        $intake = $this->intakeDao->findOneByKlant($klant);

        $intake->setAfsluitdatum(new \DateTime());

        $form = $this->createForm(IntakeCloseType::class, $intake);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->intakeDao->update($intake);
                $this->addFlash('success', 'Het dossier is afgesloten.');

                return $this->redirectToRoute('ga_klanten_view', ['id' => $klant->getId()]);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return [
            'form' => $form->createView(),
            'klant' => $klant,
            'intake' => $intake,
        ];
    }

    protected function addParams($entity)
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
