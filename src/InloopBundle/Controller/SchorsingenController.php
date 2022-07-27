<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use InloopBundle\Entity\Schorsing;
use InloopBundle\Form\SchorsingFilterType;
use InloopBundle\Form\SchorsingType;
use InloopBundle\Pdf\PdfSchorsingEn;
use InloopBundle\Pdf\PdfSchorsingNl;
use InloopBundle\Service\SchorsingDao;
use InloopBundle\Service\SchorsingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

/**
 * @Route("/schorsingen")
 * @Template
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
     * @var SchorsingDao
     */
    protected $dao;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @param SchorsingDao $dao
     * @param ExportInterface $export
     */
    public function __construct(SchorsingDao $dao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->export = $export;
    }


    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
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

        return $this->redirectToRoute('inloop_klanten_view', ['id' => $klant->getId()]);
    }

    /**
     * @Route("/add/{klant}")
     * @ParamConverter("klant", class="AppBundle:Klant")
     */
    public function addAction(Request $request)
    {
        $klant = $request->get('klant');
        $entity = new Schorsing($klant);

        $form = $this->getForm($this->formClass, $entity, [
            'medewerker' => $this->getMedewerker(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                //als geen terugkeergesprek nodig, dan  op 0 zettne. Voorkomt vervuiling.
                if(!$entity->heeftTerugkeergesprekNodig()) $entity->setTerugkeergesprekGehad(true);

                $this->dao->create($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

            } catch(UserException $e) {
//                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($entity->isAgressie()) {
                $this->sendSchorsingEmail($entity);
            }

            return $this->afterFormSubmitted($request, $entity, $form);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        if (!$this->isGranted('ROLE_TEAMLEIDERS')) {
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

    /**
     * @Route("/{schorsing}/terugkeergesprekGehad")
     * Method("POST")
     */
    public function terugkeergesprekGehad(Request $request, Schorsing $schorsing)
    {
        $schorsing->setTerugkeergesprekGehad(!$schorsing->isTerugkeergesprekGehad());
        $this->dao->update($schorsing);

        return new JsonResponse(['terugkeergesprekGehad' => $schorsing->isTerugkeergesprekGehad()]);
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
     * @param string    $language
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
            'medewerker_types' => Schorsing::DOELWITTEN,
        ]);

        /** @var \Swift_Mailer $mailer */
        $mailer = $this->container->get('mailer');

        /** @var \Swift_Mime_Message $message */
        $message = $mailer->createMessage()
            ->setFrom('noreply@deregenboog.org')
            ->setTo($this->getParameter('agressie_mail'))
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

    /**
     * Override this method so we can apply some own logic to handle submit logic.
     *
     * @param Request $request
     * @param $entity
     * @param null $form
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    protected function afterFormSubmitted(Request $request, $entity, $form = null)
    {

        if(null == $form) return parent::afterFormSubmitted($request,$entity);

        $elm = $form->has('submitAndAddIncident');
        if($elm && $form->get('submitAndAddIncident')->isClicked()) {
            $params = [
                "locatie"=>$entity->getLocaties()->first()->getId(),
                "klant"=>$entity->getKlant()->getId(),
                "redirect"=>$request->get('redirect'),
            ];
            return $this->redirectToRoute("inloop_incidenten_addprefilled",$params);
        }
        return parent::afterFormSubmitted($request, $entity);
    }
}
