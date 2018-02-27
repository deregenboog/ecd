<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Schorsing;
use InloopBundle\Form\SchorsingFilterType;
use InloopBundle\Form\SchorsingType;
use InloopBundle\Service\SchorsingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Klant;
use InloopBundle\Entity\Locatie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use HsBundle\Pdf\PdfFactuur;
use InloopBundle\Pdf\PdfSchorsingNl;
use InloopBundle\Pdf\PdfSchorsingEn;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Router;

/**
 * @Route("/schorsingen")
 */
class SchorsingenController extends AbstractController
{
    protected $title = 'Schorsingen';
    protected $entityName = 'schorsing';
    protected $entityClass = Schorsing::class;
    protected $formClass = SchorsingType::class;
    protected $filterFormClass = SchorsingFilterType::class;
    protected $baseRouteName = 'inloop_schorsingen_';

    /**
     * @var SchorsingDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\SchorsingDao")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        $entity = $this->dao->find($id);

        if ('pdf' == $this->getRequest()->get('_format')) {
            $language = $this->getRequest()->query->get('language', 'nl');

            return $this->viewPdf($entity, $language);
        }

        return [
            'entity' => $entity,
        ];
    }

    /**
     * @Route("/{klant}")
     */
    public function klantViewAction(Request $request, Klant $klant)
    {
        $schorsing = $klant->getLaatsteSchorsing();

        if ($schorsing) {
            return $this->redirectToRoute('inloop_schorsingen_view', ['id' => $klant->getLaatsteSchorsing()->getId()]);
        }

        return $this->redirect('inloop_klanten_view', ['id' => $klant->getId()]);
    }

    /**
     * @Route("/add/{klant}")
     */
    public function addAction(Request $request, Klant $klant)
    {
        $entity = new Schorsing($klant);

        $form = $this->createForm($this->formClass, $entity, [
            'medewerker' => $this->getMedewerker(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');
            } catch (\Exception $e) {
                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($entity->isAgressie()) {
                $this->sendSchorsingEmail($entity);
            }

            return $this->afterFormSubmitted($request, $entity);
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
        if (!array_key_exists(GROUP_TEAMLEIDERS, $this->userGroups)) {
            $this->addFlash('danger', 'U bent niet bevoegd schorsingen te wijzigen.');

            return $this->redirectToIndex();
        }

        return parent::editAction($request, $id);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        if (!array_key_exists(GROUP_TEAMLEIDERS, $this->userGroups)) {
            $this->addFlash('danger', 'U bent niet bevoegd schorsingen te verwijderen.');

            return $this->redirectToIndex();
        }

        return parent::deleteAction($request, $id);
    }

    /**
     * @Route("/{schorsing}/updateGezien")
     * Method("POST")
     */
    public function updateGezienAction(Request $request, Schorsing $schorsing)
    {
        $schorsing->setGezien(!$schorsing->isGezien());
        $this->dao->update($schorsing);

        return new JsonResponse(['gezien' => $schorsing->isGezien()]);
    }

    private function viewPdf(Schorsing $entity, $language)
    {
        $pdf = $this->createPdf($entity, $language);
        $response = new Response($pdf->Output(null, 'S'));

//         $filename = sprintf('schorsing-%s.pdf', $entity->getDatumVan()->format('d-m-Y'));
        $response->headers->set('Content-type', 'application/pdf');
//         $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     * @param Schorsing $entity
     * @param string $language
     *
     * @return \TCPDF
     */
    private function createPdf(Schorsing $entity, $language)
    {
        switch (strtolower($language)) {
            case 'en':
                $template = '@Inloop/schorsingen/view.en.pdf.twig';
                $pdfClass = PdfSchorsingEn::class;
                break;
            case 'nl':
            default:
                $template = '@Inloop/schorsingen/view.nl.pdf.twig';
                $pdfClass = PdfSchorsingNl::class;
                break;
        }

        $html = $this->renderView($template, ['entity' => $entity]);

        return new $pdfClass($html, $entity);
    }

    private function sendSchorsingEmail(Schorsing $schorsing)
    {
        $content = $this->renderView('InloopBundle:schorsingen:agressiemail.txt.twig', [
            'schorsing' => $schorsing,
            'medewerker' => $this->getMedewerker(),
            'url' => $this->get('router')->generate('inloop_schorsingen_view', ['id' => $schorsing->getId()], Router::ABSOLUTE_URL),
            'medewerker_types' => \Configure::read('options_medewerker'),
        ]);

        /** @var \Swift_Mailer $mailer */
        $mailer = $this->container->get('mailer');

        /** @var \Swift_Mime_Message $message */
        $message = $mailer->createMessage()
            ->setFrom('noreply@deregenboog.org')
            ->setTo(\Configure::read('agressie_mail'))
            ->setSubject('Bericht naar aanleiding van een schorsing waarbij sprake was van fysieke of verbale agressie')
            ->setBody($content, 'text/plain')
        ;

        try {
            $sent = $mailer->send($message);
        } catch (\Exception $e) {
            $sent = false;
        }

        if ($sent) {
            $this->addFlash('success', 'E-mail is verzonden.');
        } else {
            $this->addFlash('danger', 'E-mail kon niet verzonden worden.');
        }
    }
}
