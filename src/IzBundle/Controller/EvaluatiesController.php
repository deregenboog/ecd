<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use IzBundle\Entity\Evaluatie;
use IzBundle\Entity\Verslag;
use IzBundle\Form\EvaluatieType;
use IzBundle\Service\VerslagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/evaluaties")
 * @Template
 */
class EvaluatiesController extends AbstractChildController
{
    protected $title = 'Evaluaties';
    protected $entityName = 'evaluatie';
    protected $entityClass = Evaluatie::class;
    protected $formClass = EvaluatieType::class;
    protected $baseRouteName = 'iz_evaluaties_';
    protected $addMethod = 'addVerslag';

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
