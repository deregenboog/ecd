<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IzBundle\Entity\Doelgroep;
use IzBundle\Form\DoelgroepType;
use IzBundle\Service\DoelgroepDaoInterface;

/**
 * @Route("/admin/doelgroepen")
 */
class DoelgroepenController extends AbstractController
{
    protected $title = 'Doelgroepen';
    protected $entityName = 'doelgroep';
    protected $entityClass = Doelgroep::class;
    protected $formClass = DoelgroepType::class;
    protected $baseRouteName = 'iz_doelgroepen_';

    /**
     * @var DoelgroepDaoInterface
     *
     * @DI\Inject("IzBundle\Service\DoelgroepDao")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        return $this->redirectToIndex();
    }

    protected function redirectToView($entity)
    {
        return $this->redirectToIndex();
    }
}
