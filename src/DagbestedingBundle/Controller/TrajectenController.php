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
use AppBundle\Form\Model\AppDateRangeModel;
use DagbestedingBundle\Entity\Project;
use DagbestedingBundle\Form\DagdelenRangeType;
use DagbestedingBundle\Form\DagdelenRangeModel;

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
        if (!in_array(GROUP_TRAJECTBEGELEIDER, $this->userGroups)) {
            $this->addFlash('danger', 'U bent niet bevoegd trajecten aan te maken.');

            return $this->redirectToRoute('dagbesteding_trajecten_index');
        }

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
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        if (!in_array(GROUP_TRAJECTBEGELEIDER, $this->userGroups)) {
            $this->addFlash('danger', 'U bent niet bevoegd trajecten te wijzigen.');

            return $this->redirectToRoute('dagbesteding_trajecten_index');
        }

        return parent::editAction($request, $id);
    }

    /**
     * @Route("/{id}/delete")
     */
    public function deleteAction(Request $request, $id)
    {
        if (!in_array(GROUP_TRAJECTBEGELEIDER, $this->userGroups)) {
            $this->addFlash('danger', 'U bent niet bevoegd trajecten te verwijderen.');

            return $this->redirectToRoute('dagbesteding_trajecten_index');
        }

        return parent::deleteAction($request, $id);
    }

    /**
     * @Route("/close/{id}")
     */
    public function closeAction(Request $request, Traject $id)
    {
        if (!in_array(GROUP_TRAJECTBEGELEIDER, $this->userGroups)) {
            $this->addFlash('danger', 'U bent niet bevoegd trajecten af te sluiten.');

            return $this->redirectToRoute('dagbesteding_trajecten_index');
        }

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
     * @Route("/{id}/dagdelen/view/{project}/{month}")
     */
    public function viewDagdelenAction(Request $request, Traject $id, Project $project, $month)
    {
        $start = new \DateTime($month.'-01 00:00:00');
        $end = new \DateTime('last day of '.$start->format('M Y'));
        $range = new AppDateRangeModel($start, $end);

        return [
            'traject' => $id,
            'project' => $project,
            'date_range' => $range,
        ];
    }

    /**
     * @Route("/{id}/dagdelen/edit/{project}/{month}")
     */
    public function editDagdelenAction(Request $request, Traject $id, Project $project, $month)
    {
        $entity = $id;

        $start = new \DateTime($month.'-01 00:00:00');
        $end = new \DateTime('last day of '.$start->format('M Y'));
        $range = new AppDateRangeModel($start, $end);

        $form = $this->createForm(DagdelenRangeType::class, new DagdelenRangeModel($entity, $project, $range));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($entity->getId()) {
                    $this->dao->update($form->getData()->getTraject());
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
