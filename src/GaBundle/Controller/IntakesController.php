<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use GaBundle\Entity\Intake;
use GaBundle\Form\IntakeType;
use GaBundle\Service\IntakeDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/intakes")
 * @Template
 */
class IntakesController extends AbstractChildController
{
    protected $entityName = 'intake';
    protected $entityClass = Intake::class;
    protected $formClass = IntakeType::class;
    protected $allowEmpty = true;
    protected $baseRouteName = 'ga_intakes_';

    /**
     * @var IntakeDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @param IntakeDao $dao
     * @param \ArrayObject $entities
     */
    public function __construct(IntakeDao $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
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
