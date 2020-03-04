<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use GaBundle\Entity\Intake;
use GaBundle\Form\IntakeFilterType;
use GaBundle\Form\IntakeType;
use GaBundle\Service\IntakeDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/intakes")
 * @Template
 */
class IntakesController extends AbstractChildController
{
    protected $title = 'Intakes';
    protected $entityName = 'intake';
    protected $entityClass = Intake::class;
    protected $formClass = IntakeType::class;
    protected $filterFormClass = IntakeFilterType::class;
    protected $allowEmpty = true;
    protected $baseRouteName = 'ga_intakes_';

    /**
     * @var IntakeDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("GaBundle\Service\IntakeDao");
        $this->entities = $container->get("ga.intake.entities");
    
        return $previous;
    }

    /**
     * @Route("/{id}/view")
     * @Template
     */
    public function viewAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        return $this->redirectToRoute('ga_klantdossiers_view', ['id' => $entity->getDossier()->getId()]);
    }

    protected function createEntity($parentEntity = null)
    {
        return new $this->entityClass($parentEntity);
    }
}
