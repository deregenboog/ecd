<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Deelnemerstatus;
use IzBundle\Form\DeelnemerstatusType;
use IzBundle\Service\DeelnemerstatusDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/deelnemerstatussen")
 * @Template
 */
class DeelnemerstatussenController extends AbstractController
{
    protected $title = 'Deelnemerstatussen';
    protected $entityName = 'deelnemerstatus';
    protected $entityClass = Deelnemerstatus::class;
    protected $formClass = DeelnemerstatusType::class;
    protected $baseRouteName = 'iz_deelnemerstatussen_';

    /**
     * @var DeelnemerstatusDaoInterface
     *
     * @DI\Inject("IzBundle\Service\DeelnemerstatusDao")
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
