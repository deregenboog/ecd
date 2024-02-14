<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use Doctrine\ORM\EntityNotFoundException;
use OekraineBundle\Entity\Intake;
use OekraineBundle\Form\IntakeFilterType;
use OekraineBundle\Form\IntakeType;
use OekraineBundle\Form\ToegangType;
use OekraineBundle\Pdf\PdfIntake;
use OekraineBundle\Security\Permissions;
use OekraineBundle\Service\IntakeDao;
use OekraineBundle\Service\IntakeDaoInterface;
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
    protected $formClass = IntakeType::class;
    protected $filterFormClass = IntakeFilterType::class;
    protected $baseRouteName = 'oekraine_intakes_';

    /**
     * @var IntakeDao
     */
    protected $dao;

    /**
     * @param IntakeDao $dao
     */
    public function __construct(IntakeDao $dao)
    {
        $this->dao = $dao;
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
     * @Route("/add/{bezoeker}")
     * @ParamConverter("bezoeker", class="OekraineBundle\Entity\Bezoeker")
     */
    public function addAction(Request $request)
    {
        $bezoeker = $request->get('bezoeker');

        if ($bezoeker->getLaatsteIntake()) {
            $entity = clone $bezoeker->getLaatsteIntake();
            $this->getEntityManager()->detach($entity);
            $bezoeker->addIntake($entity);
        } else {
            $entity = new Intake($bezoeker);
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
//                return $this->redirectToRoute('app_bezoekeren_index');
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->afterFormSubmitted($request, $entity, null);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
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

//        $this->denyAccessUnlessGranted(
//            Permissions::EDIT,
//            $entity,
//            'Je kan alleen intakes wijzigen die in de afgelopen week zijn aangemaakt.'
//        );

//        $this->denyAccessUnlessGranted(
//            Permissions::OWNER,
//            $entity,
//            'Je kan alleen intakes wijzigen die door jezelf zijn aangemaakt.'
//        );

        return $this->processForm($request, $entity);

    }

    /**
     * @Route("/{id}/editToegang")
     */
    public function editToegangAction(Request $request, $id)
    {
        $this->formClass = ToegangType::class;
        $entity = $this->dao->find($id);
        return $this->processForm($request, $entity);

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


        ];
    }
}
