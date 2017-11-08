<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Factuur;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Controller\AbstractController;
use HsBundle\Entity\Herinnering;
use HsBundle\Form\HerinneringType;
use AppBundle\Controller\AbstractChildController;
use HsBundle\Service\HerinneringDaoInterface;

/**
 * @Route("/herinneringen")
 */
class HerinneringenController extends AbstractChildController
{
    protected $title = 'Herinneringen';
    protected $entityName = 'herinnering';
    protected $entityClass = Herinnering::class;
    protected $formClass = HerinneringType::class;
    protected $addMethod = 'addHerinnering';
    protected $baseRouteName = 'hs_herinneringen_';

    /**
     * @var HerinneringDaoInterface
     *
     * @DI\Inject("hs.dao.herinnering")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("hs.herinnering.entities")
     */
    protected $entities;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        $entity = $this->dao->find($id);

        return $this->viewPdf($entity);
    }

    private function viewPdf(Herinnering $entity)
    {
        $html = $this->renderView('@Hs/herinneringen/view.pdf.twig', ['entity' => $entity]);

        \App::import('Vendor', 'xtcpdf');
        $pdf = new \XTCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Homeservice Amsterdam');
        $pdf->setPrintHeader(false);
        $pdf->xfootertext = 'Uw betaling kunt u overmaken op bankrekeningnummer NL46 INGB 0000215793 o.v.v. factuurnummer ten name van Stichting De Regenboog Groep.';
        $pdf->SetTitle('Betalingsherinnering '.$entity->getId());
        $pdf->SetSubject('Betalingsherinnering Homeservice');
        $pdf->SetFont('helvetica', '', 10);

        $pdf->AddPage();
        $pdf->Image(('img/drg-logo-142px.jpg'), 160, 0, 40, 40);
        $pdf->writeHTMLCell(0, 0, null, 40, $html);
        $response = new Response($pdf->Output(null, 'S'));

        $filename = sprintf('homeservice-betalingsherinnering-%d.pdf', $entity->getId());
        $response->headers->set('Content-type', 'application/pdf');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }
}
