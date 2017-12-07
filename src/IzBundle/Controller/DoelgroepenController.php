<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Behandelaar;
use ClipBundle\Form\BehandelaarType;
use ClipBundle\Service\BehandelaarDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IzBundle\Form\DoelstellingType;
use IzBundle\Entity\Doelstelling;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Entity\IzProject;
use IzBundle\Entity\Doelgroep;
use IzBundle\Form\DoelgroepType;

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
     * @var BehandelaarDaoInterface
     *
     * @DI\Inject("iz.dao.doelgroep")
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
