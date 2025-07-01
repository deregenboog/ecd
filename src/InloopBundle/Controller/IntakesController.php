<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Exception\UserException;
use Doctrine\ORM\EntityNotFoundException;
use InloopBundle\Entity\AccessFields;
use InloopBundle\Entity\Intake;
use InloopBundle\Form\IntakeAndToegangType;
use InloopBundle\Form\IntakeFilterType;
use InloopBundle\Form\IntakeType;
use InloopBundle\Form\ToegangType;
use InloopBundle\Pdf\PdfIntake;
use InloopBundle\Security\Permissions;
use InloopBundle\Service\IntakeDaoInterface;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/intakes")
 *
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

    protected $accessStrategies = [];

    protected $amocVerblijfsstatus = '';

    public function __construct(IntakeDaoInterface $dao, ContainerInterface $container, $accessStrategies = [], $amocVerblijfsstatus = '')
    {
        $this->container = $container;
        $this->dao = $dao;
        $this->accessStrategies = $accessStrategies;
        $this->amocVerblijfsstatus = $amocVerblijfsstatus;
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
     *
     * @ParamConverter("klant", class="AppBundle\Entity\Klant")
     */
    public function addAction(Request $request)
    {
        $klant = $request->get('klant');

        if ($klant->getLaatsteIntake()) {
            $entity = clone $klant->getLaatsteIntake();
            $this->getEntityManager()->detach($entity);
            $klant->addIntake($entity);
            $this->formClass = IntakeType::class; // because it is not the first one, dont show toegang form.
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
            } catch (UserException $e) {
                //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
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
            'redirect' => $request->get('redirect'),
        ], $this->addParams($entity, $request));
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        if (null === $entity) {
            throw new EntityNotFoundException('Kan intake niet laden.');
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

        if ($entity->getId() == $entity->getKlant()->getEersteIntake()->getId()) {
            $this->formClass = IntakeAndToegangType::class; // because it is not the first one, dont show toegang form.
        } else {
            $this->formClass = IntakeType::class; // because it is not the first one, dont show toegang form.
        }
        
        return $this->processForm($request, $entity);
    }

    //#FARHAD /**
    //  * @Route("/{id}/editToegang")
    //  */
    // public function editToegangAction(Request $request, $id)
    // {
    //     $this->formClass = ToegangType::class;
    //     $entity = $this->dao->find($id);
    //     $return = $this->processForm($request, $entity);
    //     return $return;
    // }

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
            'amocVerblijfsstatus' => $this->amocVerblijfsstatus,
            'accessStrategies' => json_encode($this->accessStrategies),
        ];
    }
}
