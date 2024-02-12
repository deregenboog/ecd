<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Exception\AppException;
use AppBundle\Export\ExportInterface;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\ConfirmationType;
use HsBundle\Entity\Creditfactuur;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Klant;
use HsBundle\Exception\HsException;
use HsBundle\Exception\InvoiceLockedException;
use HsBundle\Form\CreditfactuurType;
use HsBundle\Form\FactuurFilterType;
use HsBundle\Form\FactuurType;
use HsBundle\Pdf\PdfFactuur;
use HsBundle\Service\FactuurDao;
use HsBundle\Service\FactuurDaoInterface;
use HsBundle\Service\FactuurFactoryInterface;
use HsBundle\Service\KlantDao;
use HsBundle\Service\KlantDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/facturen")
 * @Template
 */
class FacturenController extends AbstractChildController
{
    protected $entityName = 'factuur';
    protected $entityClass = Factuur::class;
    protected $formClass = FactuurType::class;
    protected $filterFormClass = FactuurFilterType::class;
    protected $addMethod = 'addFactuur';
    protected $baseRouteName = 'hs_facturen_';
//     protected $disabledActions = ['edit', 'delete'];

    /**
     * @var FactuurDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @var KlantDao
     */
    protected $klantDao;

    /**
     * @var FactuurFactoryInterface
     */
    private $factory;

    /**
     * @param FactuurDao $dao
     * @param \ArrayObject $entities
     * @param ExportInterface $export
     * @param KlantDao $klantDao
     * @param FactuurFactoryInterface $factory
     */
    public function __construct(FactuurDao $dao, KlantDao $klantDao, \ArrayObject $entities, ExportInterface $export,FactuurFactoryInterface $factory)
    {
        $this->dao = $dao;
        $this->entities = $entities;
        $this->export = $export;
        $this->klantDao = $klantDao;
        $this->factory = $factory;
    }


    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $filter = null;

        if ($this->filterFormClass) {
            $form = $this->getForm($this->filterFormClass);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->has('download') && $form->get('download')->isClicked()) {
                    return $this->download($form->getData());
                }
                if ($form->has('zipDownload') && $form->get('zipDownload')->isClicked()) {
                    try {
                        return $this->zipDownload($form->getData());
                    } catch (HsException $e) {
                        // ignore
                    }
                }
                if ($form->has('pdfDownload') && $form->get('pdfDownload')->isClicked()) {
                    try {
                        return $this->pdfDownload($form->getData());
                    } catch (HsException $e) {
                        // ignore
                    }
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
    public function viewAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        if (!$entity) {
            return $this->redirectToIndex();
        }

        if ('pdf' == $this->getRequest()->get('_format')) {
            return $this->viewPdf($entity);
        }

        if ($entity instanceof Creditfactuur) {
            $this->templatePath = 'credit_facturen';
        }

        return [
            'entity' => $entity,
        ];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted("ROLE_HOMESERVICE_BEHEER");
        $this->formClass = CreditfactuurType::class;
        $entity = $this->dao->find($id);
        if($entity->isLocked())
        {
            throw new InvoiceLockedException("Een definitieve factuur kan niet meer bewerkt worden");
        }

        return parent::editAction($request, $id);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {

        $this->denyAccessUnlessGranted("ROLE_HOMESERVICE_BEHEER");

        $entity = $this->dao->find($id);
        if($entity->isLocked())
        {
            throw new InvoiceLockedException("Een definitieve factuur kan niet meer verwijderd worden");
        }
        return parent::deleteAction($request, $id);


    }

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $this->entityClass = Creditfactuur::class;
        $this->formClass = CreditfactuurType::class;

        return parent::addAction($request);
    }

    /**
     * @Route("/{id}/lock")
     */
    public function lockAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_HOMESERVICE_BEHEER');

        $entity = $this->dao->find($id);
        $entity->lock();
        $this->dao->update($entity);

        return $this->redirectToView($entity);
    }

    /**
     * @Route("/{id}/oninbaar")
     * @Template
     */
    public function oninbaarAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entity->setOninbaar(true);
                $this->dao->update($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is gemarkeerd als oninbaar.');
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/inbaar")
     * @Template
     */
    public function inbaarAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entity->setOninbaar(false);
                $this->dao->update($entity);
                $this->addFlash('success', ucfirst($this->entityName).' is gemarkeerd als inbaar.');
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    protected function createEntity($parentEntity = null)
    {
        return new $this->entityClass($parentEntity);
    }

    protected function getDownloadFilename()
    {
        return sprintf(
            'hs-facturen-%s.xlsx',
            (new \DateTime())->format('Y-m-d')
        );
    }

    protected function getZipDownloadFilename()
    {
        return sprintf(
            'hs-facturen-%s.zip',
            (new \DateTime())->format('Y-m-d')
        );
    }

    protected function getPdfDownloadFilename()
    {
        return sprintf(
            'hs-facturen-%s.pdf',
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
        $filename = $this->getZipDownloadFilename();
        $collection = $this->dao->findAll(null, $filter);

        if (0 === (is_array($collection) || $collection instanceof \Countable ? count($collection) : 0)) {
            $this->addFlash('warning', 'Geen definitieve facturen gevonden.');

            throw new HsException('Geen definitieve facturen gevonden.');
        }

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

    private function pdfDownload(FilterInterface $filter)
    {
        if (!$this->export) {
            throw new AppException(get_class($this).'::export not set!');
        }

        ini_set('memory_limit', '512M');

        $dir = $this->getParameter('kernel.cache_dir');
        $filename = $this->getPdfDownloadFilename();
        $collection = $this->dao->findAll(null, $filter);

        if (0 === (is_array($collection) || $collection instanceof \Countable ? count($collection) : 0)) {
            $this->addFlash('warning', 'Geen definitieve facturen gevonden.');

            throw new HsException('Geen definitieve facturen gevonden.');
        }

        $combinedPdf = null;
        $tempNames = [];
        foreach ($collection as $factuur) {
            // create and store PDF
            $tempName = tempnam($dir, 'pdf_');
            $tempNames[] = $tempName;
            $pdf = $this->createPdf($factuur);
            $pdf->Output($tempName, 'F');

            // create or add to combined PDF
            if ($combinedPdf) {
                $tmpPdf = \Zend_Pdf::load($tempName);
                foreach ($tmpPdf->pages as $page) {
                    $combinedPdf->pages[] = clone $page;
                }
            } else {
                $combinedPdf = \Zend_Pdf::load($tempName);
            }
        }

        $tempName = tempnam($dir, 'pdf_');
        $tempNames[] = $tempName;
        $combinedPdf->save($tempName);

        $response = new Response(file_get_contents($tempName));
        $response->headers->set('Content-type', 'application/zip');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        // clean-up temp files
        foreach ($tempNames as $tempName) {
            unlink($tempName);
        }

        return $response;
    }

    private function viewPdf(Factuur $entity)
    {
        $pdf = $this->createPdf($entity);
        $response = new Response($pdf->Output(null, 'S'));

//        $filename = sprintf('homeservice-factuur-%s.pdf', $entity);
        $filename = sprintf('homeservice-factuur-%s-%s.pdf', $entity, $entity->getKlant());
        $response->headers->set('Content-type', 'application/pdf');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     * @param Factuur $entity
     *
     * @return \TCPDF
     */
    private function createPdf(Factuur $entity)
    {
        if ($entity instanceof Creditfactuur) {
            $html = $this->renderView('hs/credit_facturen/view.pdf.twig', ['entity' => $entity]);
        } else {
            $html = $this->renderView('hs/facturen/view.pdf.twig', ['entity' => $entity]);
        }

        return new PdfFactuur($html, $entity);
    }
}
