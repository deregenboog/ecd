<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Factuur;
use HsBundle\Entity\Betaling;
use HsBundle\Form\BetalingFilterType;
use HsBundle\Form\BetalingType;
use AppBundle\Controller\SymfonyController;
use Symfony\Component\Routing\Annotation\Route;
use HsBundle\Service\BetalingDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use HsBundle\Service\FactuurDaoInterface;
use AppBundle\Filter\FilterInterface;

/**
 * @Route("/hs/betalingen")
 */
class BetalingenController extends SymfonyController
{
    /**
     * @var BetalingDaoInterface
     *
     * @DI\Inject("hs.dao.betaling")
     */
    private $dao;

    /**
     * @var FactuurDaoInterface
     *
     * @DI\Inject("hs.dao.factuur")
     */
    private $factuurDao;

    private $enabledFilters = [
        'referentie',
        'datum',
        'bedrag',
        'factuur' => ['nummer'],
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

        $response = $this->render('@Hs/betalingen/download.csv.twig', ['collection' => $collection]);

        $filename = sprintf('homeservice-betalingen-%s.xls', (new \DateTime())->format('d-m-Y'));
        $response->headers->set('Content-type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     * @Route("/add/{factuurId}")
     */
    public function add($factuurId)
    {
        $factuur = $this->factuurDao->find($factuurId);
        $entity = new Betaling($factuur);

        $form = $this->getForm($entity);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dao->create($entity);
            $this->addFlash('success', 'Betaling is toegevoegd.');

            return $this->redirectToRoute('hs_facturen_view', ['id' => $factuur->getId()]);
        }

        return [
            'factuur' => $factuur,
            'form' => $form->createView(),
        ];
    }

    private function getFilter()
    {
        return $this->createForm(BetalingFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
    }

    private function getForm($data = null)
    {
        return $this->createForm(BetalingType::class, $data);
    }
}
