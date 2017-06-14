<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Deelnemer;
use DagbestedingBundle\Entity\Traject;
use DagbestedingBundle\Form\TrajectFilterType;
use DagbestedingBundle\Form\TrajectType;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use DagbestedingBundle\Service\TrajectDaoInterface;
use DagbestedingBundle\Form\DagdelenModel;
use DagbestedingBundle\Form\DagdelenType;
use AppBundle\Form\Model\AppDateRangeModel;

/**
 * @Route("/trajecten")
 */
class TrajectenController extends AbstractController
{
    protected $title = 'Trajecten';
    protected $entityName = 'Traject';
    protected $entityClass = Traject::class;
    protected $formClass = TrajectType::class;
    protected $filterFormClass = TrajectFilterType::class;
    protected $baseRouteName = 'dagbesteding_trajecten_';

    /**
     * @var TrajectDaoInterface
     *
     * @DI\Inject("dagbesteding.dao.traject")
     */
    protected $dao;

    /**
     * @var GenericExport
     *
     * @DI\Inject("dagbesteding.export.trajecten")
     */
    protected $export;

    /**
     * @Route("/add/{deelnemer}")
     */
    public function addAction(Request $request, Deelnemer $deelnemer)
    {
        $entity = new $this->entityClass();
        $entity->setDeelnemer($deelnemer);

        $form = $this->createForm($this->formClass, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($entity);
                $this->addFlash('success', $this->entityName.' is opgeslagen.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToView($entity);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/close/{id}")
     */
    public function closeAction(Request $request, Traject $id)
    {
        $entity = $id;

        $form = $this->createForm($this->formClass, $entity, [
            'mode' => 'close',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($entity);
                $this->addFlash('success', $this->entityName.' is opgeslagen.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToView($entity);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/dagdelen/edit/{month}")
     */
    public function editDagdelenAction(Request $request, Traject $id, $month)
    {
        $entity = $id;

        $start = new \DateTime($month.'-01 00:00:00');
        $end = new \DateTime('last day of '.$start->format('M Y'));
        $range = new AppDateRangeModel($start, $end);

        $form = $this->createForm(DagdelenType::class, new DagdelenModel($entity, $range));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($entity->getId()) {
                    $this->dao->updateDagdelen($form->getData());
                }
                $this->addFlash('success', 'De dagdelen zijn opgeslagen.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.'.$e->getMessage());
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }
}
