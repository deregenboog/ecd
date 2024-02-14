<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use HsBundle\Entity\Herinnering;
use HsBundle\Form\HerinneringType;
use HsBundle\Pdf\PdfHerinnering;
use HsBundle\Service\HerinneringDao;
use HsBundle\Service\HerinneringDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/herinneringen")
 * @Template
 */
class HerinneringenController extends AbstractChildController
{
    protected $entityName = 'herinnering';
    protected $entityClass = Herinnering::class;
    protected $formClass = HerinneringType::class;
    protected $addMethod = 'addHerinnering';
    protected $baseRouteName = 'hs_herinneringen_';

    /**
     * @var HerinneringDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @param HerinneringDao $dao
     * @param \ArrayObject $entities
     */
    public function __construct(HerinneringDao $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }


    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        return $this->viewPdf($entity);
    }

    private function viewPdf(Herinnering $entity)
    {
        $pdf = $this->createPdf($entity);
        $response = new Response($pdf->Output(null, 'S'));

        $filename = sprintf('homeservice-betalingsherinnering-%d.pdf', $entity->getId());
        $response->headers->set('Content-type', 'application/pdf');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    private function createPdf(Herinnering $entity)
    {
        $html = $this->renderView('hs/herinneringen/view.pdf.twig', ['entity' => $entity]);

        return new PdfHerinnering($html, $entity);
    }
}
