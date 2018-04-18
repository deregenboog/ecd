<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Koppelingstatus;
use IzBundle\Form\KoppelingstatusType;
use IzBundle\Service\KoppelingstatusDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/koppelingstatussen")
 * @Template
 */
class KoppelingstatussenController extends AbstractController
{
    protected $title = 'Koppelingstatussen';
    protected $entityName = 'koppelingstatus';
    protected $entityClass = Koppelingstatus::class;
    protected $formClass = KoppelingstatusType::class;
    protected $baseRouteName = 'iz_koppelingstatussen_';

    /**
     * @var KoppelingstatusDaoInterface
     *
     * @DI\Inject("IzBundle\Service\KoppelingstatusDao")
     */
    protected $dao;

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
