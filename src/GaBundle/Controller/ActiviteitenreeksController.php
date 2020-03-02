<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Exception\AppException;
use GaBundle\Entity\Activiteit;
use GaBundle\Form\ActiviteitenReeksModel;
use GaBundle\Form\ActiviteitenReeksType;
use GaBundle\Service\ActiviteitDaoInterface;
use GaBundle\Service\ActiviteitenreeksGenerator;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
    protected $baseRouteName = 'ga_activiteiten_';

    /**
     * @var ActiviteitDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        parent::setContainer($container);

        $this->dao = $this->get("GaBundle\Service\ActiviteitDao");
        $this->entities = $this->get("ga.activiteit.entities");
    
        return $container;
    }

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        list($parentEntity, $this->parentDao) = $this->getParentConfig($request);
        if (!$parentEntity && !$this->allowEmpty) {
            throw new AppException(sprintf('Kan geen %s aan deze entiteit toevoegen', $this->entityName));
        }

        $entity = new Activiteit();
        $entity->setGroep($parentEntity);
        $form = $this->getForm($this->formClass, new ActiviteitenReeksModel($entity), [
            'medewerker' => $this->getMedewerker(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $activiteiten = ActiviteitenreeksGenerator::generate($form->getData());
                foreach ($activiteiten as $activiteit) {
                    $activiteit->setGroep($parentEntity);
                }
                $this->dao->createBatch($activiteiten);
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
