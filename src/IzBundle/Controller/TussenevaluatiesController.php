<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use IzBundle\Entity\Tussenevaluatie;
use IzBundle\Form\VerslagType;
use IzBundle\Service\VerslagDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/tussenevaluaties")
 * @Template
 */
class TussenevaluatiesController extends AbstractChildController
{
    protected $title = 'Tussenevaluaties';
    protected $entityName = 'tussenevaluatie';
    protected $entityClass = Tussenevaluatie::class;
    protected $formClass = VerslagType::class;
    protected $baseRouteName = 'iz_tussenevaluaties_';
    protected $addMethod = 'addTussenevaluatie';

    /**
     * @var VerslagDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct()
    {
        $this->dao = $this->get("IzBundle\Service\VerslagDao");
        $this->entities = $this->get("iz.verslag.entities");
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
