<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use IzBundle\Entity\Eindevaluatie;
use IzBundle\Form\VerslagType;
use IzBundle\Service\VerslagDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/eindevaluaties")
 *
 * @Template
 */
class EindevaluatiesController extends AbstractChildController
{
    use DisableIndexActionTrait;

    protected $entityName = 'eindevaluatie';
    protected $entityClass = Eindevaluatie::class;
    protected $formClass = VerslagType::class;
    protected $baseRouteName = 'iz_eindevaluaties_';
    protected $addMethod = 'addEindevaluatie';

    /**
     * @var VerslagDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(VerslagDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }

    protected function redirectToView($entity)
    {
        return $this->redirectToIndex();
    }
}
