<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Exception\AppException;
use GaBundle\Entity\Activiteit;
use GaBundle\Form\ActiviteitenReeksModel;
use GaBundle\Form\ActiviteitenReeksType;
use GaBundle\Service\ActiviteitDaoInterface;
use GaBundle\Service\ActiviteitenreeksGenerator;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/activiteitenreeks")
 * @Template
 */
class ActiviteitenreeksController extends AbstractChildController
{
    protected $title = 'Reeks activiteiten';
    protected $entityName = 'reeks activiteiten';
    protected $entityClass = Activiteit::class;
    protected $formClass = ActiviteitenReeksType::class;
    protected $addMethod = 'addActiviteit';
    protected $baseRouteName = 'ga_activiteiten_';

    /**
     * @var ActiviteitDaoInterface
     *
     * @DI\Inject("GaBundle\Service\ActiviteitDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("ga.activiteit.entities")
     */
    protected $entities;

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        if (!$this->addMethod && !$this->allowEmpty) {
            throw new \RuntimeException('Property $addMethod must be set in class '.get_class($this));
        }

        list($parentEntity, $this->parentDao) = $this->getParentConfig($request);
        if (!$parentEntity && !$this->allowEmpty) {
            throw new AppException(sprintf('Kan geen %s aan deze entiteit toevoegen', $this->entityName));
        }

        $entity = $this->createEntity($parentEntity);
        if ($parentEntity && $this->addMethod) {
            $parentEntity->{$this->addMethod}($entity);
        }

        $form = $this->createForm($this->formClass, new ActiviteitenReeksModel($entity), [
            'medewerker' => $this->getMedewerker(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $activiteiten = ActiviteitenreeksGenerator::generate($form->getData());
                foreach ($activiteiten as $activiteit) {
                    $this->dao->create($activiteit);
                }
                $this->addFlash('success', count($activiteiten).' activiteiten zijn toegevoegd.');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            if ($parentEntity) {
                return $this->redirectToView($parentEntity);
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'parent_entity' => $parentEntity,
            'form' => $form->createView(),
        ];
    }
}
