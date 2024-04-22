<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use Doctrine\ORM\EntityNotFoundException;
use InloopBundle\Entity\Intake;
use InloopBundle\Form\FirstIntakeType;
use InloopBundle\Form\IntakeAndToegangType;
use InloopBundle\Form\IntakeFilterType;
use InloopBundle\Form\IntakeType;
use InloopBundle\Form\ToegangType;
use InloopBundle\Pdf\PdfIntake;
use InloopBundle\Security\Permissions;
use InloopBundle\Service\IntakeDaoInterface;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/intakes")
 * @Template
 */
class IntakesController extends AbstractController
{
    protected $entityName = 'intake';
    protected $entityClass = Intake::class;
    protected $formClass = IntakeAndToegangType::class;
    protected $filterFormClass = IntakeFilterType::class;
    protected $baseRouteName = 'inloop_intakes_';

    /**
     * @var IntakeDaoInterface
     */
    protected $dao;

    /**
     * @var array|mixed $tbc_countries
     * Array of countries (value=name) which klnanten needs to be tested against TBC and trhow a warning.
     */
    protected $tbc_countries = array();

    protected $accessStrategies = [];

    protected $verblijfsstatusEuropeesBurger;

    protected $verblijfsstatusIllegaal;

    public function __construct(
        IntakeDaoInterface $dao,
        ContainerInterface $container,
        $tbc_countries = [],
        $accessStrategies = [],
        $verblijfsstatusEuropeesBurger = '',
        $verblijfsstatusIllegaal = ''
    ) {
        $this->container = $container;
        $this->dao = $dao;
        $this->tbc_countries = $tbc_countries;
        $this->accessStrategies = $accessStrategies;
        $this->verblijfsstatusEuropeesBurger = $verblijfsstatusEuropeesBurger;
        $this->verblijfsstatusIllegaal = $verblijfsstatusIllegaal;
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        if ('pdf' == $this->getRequest()->get('_format')) {
            return $this->viewPdf($entity);
        }

        return [
            'entity' => $entity,
        ];
    }

    /**
     * @Route("/add/{klant}")
     * @ParamConverter("klant", class="AppBundle\Entity\Klant")
     */
    public function addAction(Request $request)
    {
        $klant = $request->get('klant');

        if ($klant->getLaatsteIntake()) {
            $entity = clone $klant->getLaatsteIntake();
            $this->getEntityManager()->detach($entity);
            $klant->addIntake($entity);
            $this->formClass = IntakeType::class;//because it is not the first one, dont show toegang form.
        } else {
            $entity = new Intake($klant);
        }

        $form = $this->getForm($this->formClass, $entity, [
            'medewerker' => $this->getMedewerker(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->afterFormSubmitted($request, $entity, null);
        }

        return array_merge([
            'entity' => $entity,
            'form' => $form->createView(),
            'redirect'=>$request->get('redirect')
        ], $this->addParams($entity, $request));

    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {

         $entity = $this->dao->find($id);

        if($entity === null)
        {
            throw new EntityNotFoundException("Kan intake niet laden.");
        }


        $this->denyAccessUnlessGranted(
            Permissions::EDIT,
            $entity,
            'Je kan alleen intakes wijzigen die in de afgelopen week zijn aangemaakt.'
        );

        $this->denyAccessUnlessGranted(
            Permissions::OWNER,
            $entity,
            'Je kan alleen intakes wijzigen die door jezelf zijn aangemaakt.'
        );


        if($entity->getId() == $entity->getKlant()->getEersteIntake()->getId())
        {
            $this->formClass = IntakeAndToegangType::class;//because it is not the first one, dont show toegang form.
        }
        else {
            $this->formClass = IntakeType::class;//because it is not the first one, dont show toegang form.
        }

        return $this->processForm($request, $entity);

    }

    /**
     * @Route("/{id}/editToegang")
     */
    public function editToegangAction(Request $request, $id)
    {
        $this->formClass = ToegangType::class;
        $entity = $this->dao->find($id);
        $return = $this->processForm($request, $entity);

        return $return;
    }


    /**
     * @Route("/form.pdf")
     */
    public function formAction(Request $request)
    {
        $entity = new Intake();
        $form = $this->getForm($this->formClass, $entity);

        $html = $this->renderView('inloop/intakes/form.pdf.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
        ]);

        $pdf = new PdfIntake($html, $entity);

        $response = new Response($pdf->Output(null, 'S'));
        $response->headers->set('Content-type', 'application/pdf');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    private function viewPdf(Intake $entity)
    {
        $html = $this->renderView('inloop/intakes/view.pdf.twig', ['entity' => $entity]);
        $pdf = new PdfIntake($html, $entity);

        $response = new Response($pdf->Output(null, 'S'));
        $response->headers->set('Content-type', 'application/pdf');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    protected function addParams($entity, Request $request): array
    {
        return [
            'tbc_countries' => $this->tbc_countries,
            'verblijfsstatus_europees_burger' => $this->verblijfsstatusEuropeesBurger,
            'verblijfsstatus_illegaal' => $this->verblijfsstatusIllegaal,
            'accessStrategies' => json_encode($this->accessStrategies),
        ];
    }
}
