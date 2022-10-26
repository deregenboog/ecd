<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Vrijwilliger;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\VrijwilligerFilterType;
use AppBundle\Service\VrijwilligerDao;
use GaBundle\Entity\VrijwilligerIntake;
use GaBundle\Entity\VrijwilligerLidmaatschap;
use GaBundle\Entity\VrijwilligerVerslag;
use GaBundle\Form\AanmeldingType;
use GaBundle\Form\VrijwilligerIntakeFilterType;
use GaBundle\Form\VrijwilligerIntakeType;
use GaBundle\Service\VrijwilligerIntakeDao;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vrijwilligerintakes")
 * @Template
 */
class VrijwilligerintakesController extends AbstractController
{
    protected $title = 'Vrijwilligers';
    protected $entityName = 'vrijwilliger';
    protected $entityClass = VrijwilligerIntake::class;
    protected $formClass = VrijwilligerIntakeType::class;
    protected $filterFormClass = VrijwilligerIntakeFilterType::class;
    protected $baseRouteName = 'ga_vrijwilligerintakes_';

    /**
     * @var VrijwilligerIntakeDao 
     */
    protected $dao;

    /**
     * @var VrijwilligerDao 
     */
    private $vrijwilligerDao;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @param VrijwilligerIntakeDao $dao
     * @param VrijwilligerDao $vrijwilligerDao
     * @param ExportInterface $export
     */
    public function __construct(VrijwilligerIntakeDao $dao, VrijwilligerDao $vrijwilligerDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->vrijwilligerDao = $vrijwilligerDao;
        $this->export = $export;
    }


    /**
     * @Route("/add")
     * @Template
     */
    public function addAction(Request $request)
    {
        if ($request->get('vrijwilliger')) {
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

        $form = $this->getForm(AanmeldingType::class, $aanmelding);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->intakeDao->update($intake);
                $this->addFlash('success', 'Het dossier is heropend.');

                return $this->redirectToRoute('ga_vrijwilligers_view', ['id' => $intake->getId()]);
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return [
            'form' => $form->createView(),
            'deelnemer' => $intake,
        ];
    }

    protected function doSearch(Request $request)
    {
        $form = $this->getForm(VrijwilligerFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'bsn', 'geboortedatum'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $count = (int) $this->vrijwilligerDao->countAll($form->getData());
            if (0 === $count) {
                $this->addFlash('info', sprintf('De zoekopdracht leverde geen resultaten op. Maak een nieuwe %s aan.', $this->entityName));

                return $this->redirectToRoute($this->baseRouteName.'add', ['vrijwilliger' => 'new']);
            }

            if ($count > 100) {
                $form->addError(new FormError('De zoekopdracht leverde teveel resultaten op. Probeer het opnieuw met een specifiekere zoekopdracht.'));

                return [
                    'form' => $form->createView(),
                ];
            }

            return [
                'form' => $form->createView(),
                'vrijwilligers' => $this->vrijwilligerDao->findAll(null, $form->getData()),
            ];
        }

        return [
            'form' => $form->createView(),
        ];
    }

    protected function doAdd(Request $request)
    {
        $vrijwilligerId = $request->get('vrijwilliger');
        if ('new' === $vrijwilligerId) {
            $vrijwilliger = new Vrijwilliger();
        } else {
            $vrijwilliger = $this->vrijwilligerDao->find($vrijwilligerId);
        }

        // redirect if already exists
        $intake = $this->dao->findOneByVrijwilliger($vrijwilliger);
        if ($intake) {
            return $this->redirectToView($intake);
        }

        $intake = new VrijwilligerIntake($vrijwilliger);
        $form = $this->getForm($this->formClass, $intake);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($intake);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                return $this->redirectToView($intake);
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
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
        $lidmaatschappen = $this->getDoctrine()->getRepository(VrijwilligerLidmaatschap::class)
            ->findBy(['vrijwilliger' => $entity->getVrijwilliger()], ['created' => 'desc']);

        $verslagen = $this->getDoctrine()->getRepository(VrijwilligerVerslag::class)
            ->findBy(['vrijwilliger' => $entity->getVrijwilliger()], ['created' => 'desc']);

        return [
            'lidmaatschappen' => $lidmaatschappen,
            'verslagen' => $verslagen,
        ];
    }
}
