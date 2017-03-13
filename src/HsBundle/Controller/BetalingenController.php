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
        $filter = $this->getFilter()->handleRequest($request);
        if ($filter->isSubmitted() && $filter->isValid()) {
            $pagination = $this->dao->findAll($request->get('page', 1), $filter->getData());
        } else {
            $pagination = $this->dao->findAll($request->get('page', 1));
        }

        return [
            'filter' => $filter->createView(),
            'pagination' => $pagination,
        ];
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

            return $this->redirectToIndexAction();
        }

        return [
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
