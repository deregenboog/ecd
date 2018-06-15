<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use IzBundle\Entity\Verslag;
use IzBundle\Form\VerslagType;
use IzBundle\Service\DoelgroepDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/verslagen")
 */
class VerslagenController extends AbstractChildController
{
    protected $title = 'Verslagen';
    protected $entityName = 'verslag';
    protected $entityClass = Verslag::class;
    protected $formClass = VerslagType::class;
    protected $baseRouteName = 'iz_verslagen_';
    protected $addMethod = 'addVerslag';

    /**
     * @var DoelgroepDaoInterface
     *
     * @DI\Inject("IzBundle\Service\DoelgroepDao")
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
