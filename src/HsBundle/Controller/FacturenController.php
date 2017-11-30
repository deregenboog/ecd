<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Factuur;
use HsBundle\Form\FactuurFilterType;
use Symfony\Component\Routing\Annotation\Route;
use HsBundle\Service\FactuurDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use HsBundle\Entity\Klant;
use HsBundle\Service\FactuurFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use HsBundle\Form\FactuurType;
use HsBundle\Service\KlantDaoInterface;
use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\ExportInterface;
use AppBundle\Filter\FilterInterface;
use AppBundle\Exception\AppException;
use HsBundle\Exception\HsException;

/**
 * @Route("/facturen")
 */
class FacturenController extends AbstractChildController
{
    protected $title = 'Facturen';
    protected $entityName = 'factuur';
    protected $entityClass = Factuur::class;
    protected $formClass = FactuurType::class;
    protected $filterFormClass = FactuurFilterType::class;
    protected $baseRouteName = 'hs_facturen_';
    protected $disabledActions = ['edit', 'delete'];

    /**
     * @var FactuurDaoInterface
     *
     * @DI\Inject("hs.dao.factuur")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("hs.factuur.entities")
     */
    protected $entities;

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
        $filter = null;

        if ($this->filterFormClass) {
            $form = $this->createForm($this->filterFormClass);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->has('download') && $form->get('download')->isClicked()) {
                    return $this->download($form->getData());
                }
                if ($form->has('zipDownload') && $form->get('zipDownload')->isClicked()) {
                    return $this->zipDownload($form->getData());
                }
            }
            $filter = $form->getData();
        }

        $page = $request->get('page', 1);
        $pagination = $this->dao->findAll($page, $filter);

        return [
            'filter' => isset($form) ? $form->createView() : null,
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
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
     * @Route("/{id}/lock")
     */
    public function lockAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted(GROUP_HOMESERVICE_BEHEER);

        $entity = $this->dao->find($id);
        $entity->lock();
        $this->dao->update($entity);

        return $this->redirectToView($entity);
    }

    protected function getDownloadFilename()
    {
        return sprintf(
            'hs-facturen-%s.zip',
            (new \DateTime())->format('Y-m-d')
            );
    }

    private function zipDownload(FilterInterface $filter)
    {
        if (!$this->export) {
            throw new AppException(get_class($this).'::export not set!');
        }

        ini_set('memory_limit', '512M');

        $dir = $this->getParameter('kernel.cache_dir');
        $filename = $this->getDownloadFilename();
        $collection = $this->dao->findAll(null, $filter);

        @unlink($dir.'/'.$filename);

        $zip = new \ZipArchive();
        if (!$zip->open($dir.'/'.$filename, \ZipArchive::CREATE)) {
            throw new HsException('ZIP archive could not be created.');
        }
        foreach ($collection as $factuur) {
            $pdf = $this->createPdf($factuur);
            $localName = str_replace('/', '-', $factuur->getNummer()).'.pdf';
            $zip->addFromString($localName, $pdf->Output(null, 'S'));
        }
        $zip->close();

        $response = new Response(file_get_contents($dir.'/'.$filename));
        $response->headers->set('Content-type', 'application/zip');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        unlink($dir.'/'.$filename);

        return $response;
    }

    private function viewPdf(Factuur $entity)
    {
        $pdf = $this->createPdf($entity);
        $response = new Response($pdf->Output(null, 'S'));

        $filename = sprintf('homeservice-factuur-%s.pdf', $entity);
        $response->headers->set('Content-type', 'application/pdf');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     * @param Factuur $entity
     *
     * @return \XTCPDF
     */
    private function createPdf(Factuur $entity)
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

        return $pdf;
    }
}
