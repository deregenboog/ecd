<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use IzBundle\Entity\Eindevaluatie;
use IzBundle\Form\VerslagType;
use IzBundle\Service\VerslagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/eindevaluaties")
 * @Template
 */
class EindevaluatiesController extends AbstractChildController
{
    protected $title = 'Eindevaluaties';
    protected $entityName = 'eindevaluatie';
    protected $entityClass = Eindevaluatie::class;
    protected $formClass = VerslagType::class;
    protected $baseRouteName = 'iz_eindevaluaties_';
    protected $addMethod = 'addEindevaluatie';

    /**
     * @var VerslagDaoInterface
     *
     * @DI\Inject("IzBundle\Service\VerslagDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("iz.verslag.entities")
     */
    protected $entities;

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
