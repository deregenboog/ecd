<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Factuur;
use AppBundle\Form\ConfirmationType;
use HsBundle\Entity\Klus;
use HsBundle\Form\FactuurFilterType;
use AppBundle\Controller\SymfonyController;
use Symfony\Component\Routing\Annotation\Route;
use HsBundle\Service\FactuurDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Filter\FilterInterface;
use HsBundle\Entity\Klant;
use HsBundle\Service\FactuurFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use HsBundle\Entity\Declaratie;
use HsBundle\Entity\DeclaratieCategorie;

/**
 * @Route("/hs/facturen")
 */
class FacturenController extends SymfonyController
{
    /**
     * @var FactuurDaoInterface
     *
     * @DI\Inject("hs.dao.factuur")
     */
    private $dao;

    /**
     * @var FactuurFactoryInterface
     *
     * @DI\Inject("hs.factory.factuur")
     */
    private $factory;

    private $enabledFilters = [
        'nummer',
        'datum',
        'bedrag',
        'negatiefSaldo',
        'klant' => ['naam'],
        'filter',
        'download',
    ];

    /**
     * @Route("/")
     */
    public function index(Request $request)
    {
        $form = $this->getFilter()->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filter = $form->getData();
        } else {
            $filter = null;
        }

        if ($form->get('download')->isClicked()) {
            return $this->download($filter);
        }

        $pagination = $this->dao->findAll($request->get('page', 1), $filter);

        return [
            'filter' => $form->createView(),
            'pagination' => $pagination,
        ];
    }

    private function download(FilterInterface $filter)
    {
        $collection = $this->dao->findAll(0, $filter);

        $response = $this->render('@Hs/facturen/download.csv.twig', ['collection' => $collection]);

        $filename = sprintf('homeservice-facturen-%s.xls', (new \DateTime())->format('d-m-Y'));
        $response->headers->set('Content-type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     * @Route("/{id}/view")
     */
    public function view(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        if ($request->get('_format') == 'pdf') {
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
        $pdf->xfootertext = 'Uw betaling kunt u overmaken op bankrekeningnummer NL46 INGB 0000215793 o.v.v. factuurnummer ten name van Stichting de Regenboog.';
        $pdf->SetTitle("Factuur $entity");
        $pdf->SetSubject('Factuur Homeservice');
        $pdf->SetFont('helvetica', '', 10);

        $pdf->AddPage();
        $pdf->writeHTMLCell(0, 0, null, null, $html);
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
    public function add(Klant $klant)
    {
        $factuur = $this->factory->create($klant);

        if (!$factuur->isEmpty()) {
            $this->dao->create($factuur);
            $this->addFlash('success', 'Factuur is toegevoegd.');
        } else {
            $this->addFlash('info', 'Er is niks te factureren.');
        }

        return $this->redirectToRoute('hs_klanten_view', ['id' => $klant->getId()]);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $entity = $this->dao->find($id);

        $form = $this->getForm($entity);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dao->update($entity);
            $this->addFlash('success', 'Factuur is opgeslagen.');

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $this->dao->delete($entity);
                $this->addFlash('success', 'Factuur is verwijderd.');
            }

            return $this->redirectToIndexAction();
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    private function getFilter()
    {
        return $this->createForm(FactuurFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
    }

    private function getForm($data = null)
    {
        return $this->createForm(BetalingType::class, $data);
    }

    private function redirectToIndexAction()
    {
        return $this->redirectToRoute('hs_facturen_index');
    }

    private function redirectToView(Factuur $entity)
    {
        return $this->redirectToRoute('hs_facturen_view', ['id' => $entity->getId()]);
    }
}
