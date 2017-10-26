<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Factuur;
use HsBundle\Form\FactuurFilterType;
use Symfony\Component\Routing\Annotation\Route;
use HsBundle\Service\FactuurDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use HsBundle\Entity\Klant;
use HsBundle\Service\FactuurFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Controller\AbstractController;
use HsBundle\Form\FactuurType;
use HsBundle\Service\KlantDaoInterface;

/**
 * @Route("/facturen")
 */
class FacturenController extends AbstractController
{
    protected $title = 'Facturen';
    protected $entityName = 'factuur';
    protected $entityClass = Factuur::class;
    protected $formClass = FactuurType::class;
    protected $filterFormClass = FactuurFilterType::class;
    protected $baseRouteName = 'hs_facturen_';

    /**
     * @var FactuurDaoInterface
     *
     * @DI\Inject("hs.dao.factuur")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("hs.export.factuur")
     */
    protected $export;

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("hs.dao.klant")
     */
    protected $klantDao;

    /**
     * @var FactuurFactoryInterface
     *
     * @DI\Inject("hs.factory.factuur")
     */
    private $factory;

    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $response = parent::indexAction($request);

        if (is_array($response)) {
            $dateRange = $this->factory->getDateRange();
            $response['aantal_klanten_facturabel'] = $this->klantDao->countFacturabel($dateRange);
            $response['einddatum_facturatie'] = $dateRange->getEnd();
        }

        return $response;
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        if ('pdf' == $request->get('_format')) {
            return $this->viewPdf($entity);
        }

        return [
            'entity' => $entity,
        ];
    }

    private function viewPdf(Factuur $entity)
    {
        $html = $this->renderView('@Hs/facturen/view.pdf.twig', ['entity' => $entity]);

        \App::import('Vendor', 'xtcpdf');
        $pdf = new \XTCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Homeservice Amsterdam');
        $pdf->setPrintHeader(false);
        $pdf->xfootertext = 'Uw betaling kunt u overmaken op bankrekeningnummer NL46 INGB 0000215793 o.v.v. factuurnummer ten name van Stichting De Regenboog Groep.';
        $pdf->SetTitle('Factuur '.$entity);
        $pdf->SetSubject('Factuur Homeservice');
        $pdf->SetFont('helvetica', '', 10);

        $pdf->AddPage();
        $pdf->Image(('img/drg-logo-142px.jpg'), 160, 0, 40, 40);
        $pdf->writeHTMLCell(0, 0, null, 40, $html);
        $response = new Response($pdf->Output(null, 'S'));

        $filename = sprintf('homeservice-factuur-%s.pdf', $entity);
        $response->headers->set('Content-type', 'application/pdf');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     * @Route("/add/{klant}")
     * @ParamConverter()
     */
    public function addAction(Request $request, Klant $klant)
    {
        $factuur = $this->factory->create($klant);

        if (!$factuur->isEmpty()) {
            $this->dao->create($factuur);
            $this->addFlash('success', 'Factuur is toegevoegd.');
        } else {
            $this->addFlash('info', 'Er is op dit moment niks te factureren.');
        }

        if ($url = $request->get('redirect')) {
            return $this->redirect($url);
        }

        return $this->redirectToRoute('hs_klanten_view', ['id' => $klant->getId()]);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        return $this->redirectToView($entity);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        return $this->redirectToView($entity);
    }

    /**
     * @Route("/batch")
     */
    public function batchAction()
    {
        $klanten = $this->klantDao->findFacturabel($this->factory->getDateRange());

        $facturen = [];
        foreach ($klanten as $klant) {
            $factuur = $this->factory->create($klant);
            if (!$factuur->isEmpty()) {
                $facturen[] = $factuur;
            }
        }

        if (count($facturen) > 0) {
            $this->dao->createBatch($facturen);
            $this->addFlash('success', count($facturen).' facturen zijn toegevoegd.');
        } else {
            $this->addFlash('info', 'Er is op dit moment niks te factureren.');
        }

        return $this->redirectToIndex();
    }
}
