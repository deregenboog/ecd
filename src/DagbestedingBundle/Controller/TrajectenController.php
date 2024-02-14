<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Exception\UserException;
use AppBundle\Export\GenericExport;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\Model\AppDateRangeModel;
use DagbestedingBundle\Entity\Project;
use DagbestedingBundle\Entity\Traject;
use DagbestedingBundle\Form\DagdelenRangeModel;
use DagbestedingBundle\Form\DagdelenRangeType;
use DagbestedingBundle\Form\TrajectFilterType;
use DagbestedingBundle\Form\TrajectType;
use DagbestedingBundle\Service\TrajectDao;
use DagbestedingBundle\Service\TrajectDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/trajecten")
 * @Template
 */
class TrajectenController extends AbstractChildController
{
    protected $entityName = 'Traject';
    protected $entityClass = Traject::class;
    protected $formClass = TrajectType::class;
    protected $filterFormClass = TrajectFilterType::class;
    protected $baseRouteName = 'dagbesteding_trajecten_';
    protected $addMethod = 'addTraject';

    /**
     * @var TrajectDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @var GenericExport
     */
    protected $export;

    /**
     * @param TrajectDao $dao
     * @param \ArrayObject $entities
     * @param GenericExport $export
     */
    public function __construct(TrajectDao $dao, \ArrayObject $entities, GenericExport $export)
    {
        $this->dao = $dao;
        $this->entities = $entities;
        $this->export = $export;
    }


    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        if (!$this->isGranted('ROLE_DAGBESTEDING')) {
            $this->addFlash('danger', 'U bent niet bevoegd trajecten aan te maken.');

            return $this->redirectToRoute('dagbesteding_trajecten_index');
        }

        return parent::addAction($request);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        if (!$this->isGranted('ROLE_DAGBESTEDING')) {
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
        if (!$this->isGranted('ROLE_DAGBESTEDING')) {
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
        if (!$this->isGranted('ROLE_DAGBESTEDING')) {
            $this->addFlash('danger', 'U bent niet bevoegd trajecten af te sluiten.');

            return $this->redirectToRoute('dagbesteding_trajecten_index');
        }

        $entity = $id;

        if (!$entity->isActief() && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('danger', 'U bent niet bevoegd de afsluiting van dit traject te wijzigen.');

            return $this->redirectToRoute('dagbesteding_trajecten_index');
        }

        $this->getEntityManager()->getFilters()->enable('active');
        $form = $this->getForm($this->formClass, $entity, [
            'mode' => 'close',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);
                $this->addFlash('success', $this->entityName.' is afgesloten.');
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            }  catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
            if($cd = $form->get('closeDeelnemer')->getData() !== false)
            {
                //redirect to close deelnemer.
                return $this->redirectToRoute('dagbesteding_deelnemers_close',["id"=>$entity->getDeelnemer()->getId()]);
            }

            return $this->redirectToView($entity);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/open/{id}")
     */
    public function openAction(Request $request, Traject $id)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('danger', 'U bent niet bevoegd trajecten af te heropenen.');

            return $this->redirectToRoute('dagbesteding_trajecten_index');
        }

        $entity = $id;

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $entity->open();
                    $this->dao->update($entity);
                    $this->addFlash('success', $this->entityName.' is heropend.');
                } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                    $message =  $e->getMessage();
                    $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
                }  catch (\Exception $e) {
                    $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                    $this->addFlash('danger', $message);
                }
            }

            return $this->redirectToView($entity);
        }

        return [
            'form' => $form->createView(),
            'entity' => $entity,
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

        $form = $this->getForm(DagdelenRangeType::class, new DagdelenRangeModel($entity, $project, $range));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($entity->getId()) {
                    $this->dao->update($form->getData()->getTraject());
                }
                $this->addFlash('success', 'De dagdelen zijn opgeslagen.');
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            }  catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'project' => $project,
            'form' => $form->createView(),
        ];
    }


}
