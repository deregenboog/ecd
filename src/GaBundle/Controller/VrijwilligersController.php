<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\ExportInterface;
use GaBundle\Entity\VrijwilligerDeelname;
use GaBundle\Entity\VrijwilligerIntake;
use GaBundle\Entity\VrijwilligerLidmaatschap;
use GaBundle\Entity\VrijwilligerVerslag;
use GaBundle\Form\AanmeldingType;
use GaBundle\Form\AfsluitingType;
use GaBundle\Form\VrijwilligerdossierFilterType;
use GaBundle\Form\VrijwilligerdossierType;
use GaBundle\Service\VrijwilligerDaoInterface;
use GaBundle\Service\VrijwilligerIntakeDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use GaBundle\Entity\Document;
use AppBundle\Entity\Vrijwilliger;

/**
 * @Route("/vrijwilligers")
 */
class VrijwilligersController extends AbstractController
{
    protected $title = 'Vrijwilligers';
    protected $entityName = 'vrijwilliger';
    protected $entityClass = VrijwilligerIntake::class;
    protected $formClass = VrijwilligerdossierType::class;
    protected $filterFormClass = VrijwilligerdossierFilterType::class;
    protected $baseRouteName = 'ga_vrijwilligers_';

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("GaBundle\Service\VrijwilligerDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("ga.export.vrijwilligerdossiers")
     */
    protected $export;

    /**
     * @var VrijwilligerIntakeDaoInterface
     *
     * @DI\Inject("GaBundle\Service\VrijwilligerIntakeDao")
     */
    private $intakeDao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        $vrijwilliger = $this->dao->find($id);

        $intake = $this->intakeDao->findOneByVrijwilliger($vrijwilliger);

        return $this->redirectToRoute('ga_vrijwilligerintakes_view', ['id' => $intake->getId()]);
    }

    /**
     * @Route("/{id}/view/verslagen")
     */
    public function viewVerslagenAction(Request $request, $id)
    {
        $vrijwilliger = $this->dao->find($id);

        $intake = $this->intakeDao->findOneByVrijwilliger($vrijwilliger);

        $verslagen = $this->getDoctrine()->getRepository(VrijwilligerVerslag::class)
            ->findBy(['vrijwilliger' => $vrijwilliger], ['created' => 'desc']);

        return [
            'entity' => $vrijwilliger,
            'intake' => $intake,
            'verslagen' => $verslagen,
        ];
    }

    /**
     * @Route("/{id}/view/groepen")
     */
    public function viewGroepenAction(Request $request, $id)
    {
        $vrijwilliger = $this->dao->find($id);

        $intake = $this->intakeDao->findOneByVrijwilliger($vrijwilliger);

        $lidmaatschappen = $this->getDoctrine()->getRepository(VrijwilligerLidmaatschap::class)
            ->findBy(['vrijwilliger' => $vrijwilliger], ['created' => 'desc']);

        return [
            'entity' => $vrijwilliger,
            'intake' => $intake,
            'lidmaatschappen' => $lidmaatschappen,
        ];
    }

    /**
     * @Route("/{id}/view/activiteiten")
     */
    public function viewActiviteitenAction(Request $request, $id)
    {
        $vrijwilliger = $this->dao->find($id);

        $intake = $this->intakeDao->findOneByVrijwilliger($vrijwilliger);

        $deelnames = $this->getDoctrine()->getRepository(VrijwilligerDeelname::class)
            ->findBy(['vrijwilliger' => $vrijwilliger], ['created' => 'desc']);

        return [
            'entity' => $vrijwilliger,
            'intake' => $intake,
            'deelnames' => $deelnames,
        ];
    }

    /**
     * @Route("/{id}/view/documenten")
     */
    public function viewDocumentenAction(Request $request, $id)
    {
        $vrijwilliger = $this->dao->find($id);

        $intake = $this->intakeDao->findOneByVrijwilliger($vrijwilliger);

        $documenten = $this->getDoctrine()->getRepository(Document::class)
            ->findBy(['vrijwilliger' => $vrijwilliger]);

        return [
            'entity' => $vrijwilliger,
            'intake' => $intake,
            'documenten' => $documenten,
        ];
    }

    /**
     * @Route("/{id}/view/afsluiting")
     */
    public function viewAfsluitingAction(Request $request, $id)
    {
        $vrijwilliger = $this->dao->find($id);

        $intake = $this->intakeDao->findOneByVrijwilliger($vrijwilliger);

        $lidmaatschappen = $this->getDoctrine()->getRepository(VrijwilligerLidmaatschap::class)
            ->findBy(['vrijwilliger' => $vrijwilliger, 'einddatum' => null]);

        return [
            'entity' => $vrijwilliger,
            'intake' => $intake,
            'lidmaatschappen' => $lidmaatschappen,
        ];
    }

    /**
     * @Route("/{id}/open")
     */
    public function openAction($id)
    {
        $dossier = $this->dao->find($id);

        $aanmelding = new Aanmelding();
        $dossier->addAanmelding($aanmelding);

        $form = $this->createForm(AanmeldingType::class, $aanmelding);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($dossier);
                $this->addFlash('success', 'Het dossier is heropend.');

                return $this->redirectToRoute($this->baseRouteName.'view', ['id' => $dossier->getId()]);
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
        $dossier = $this->dao->find($id);

        $afsluiting = new Afsluiting();
        $intake->addAfsluiting($afsluiting);

        $form = $this->createForm(AfsluitingType::class, $afsluiting);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($intake);
                $this->addFlash('success', 'Het dossier is afgesloten.');

                return $this->redirectToRoute($this->baseRouteName.'view', ['id' => $intake->getId()]);
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
}
