<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Service\BehandelaarDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IzBundle\Form\DoelstellingType;
use IzBundle\Entity\Doelstelling;
use IzBundle\Form\DoelstellingFilterType;

/**
 * @Route("/admin/doelstellingen")
 */
class DoelstellingenController extends AbstractController
{
    protected $title = 'Doelstellingen';
    protected $entityName = 'doelstelling';
    protected $entityClass = Doelstelling::class;
    protected $formClass = DoelstellingType::class;
    protected $filterFormClass = DoelstellingFilterType::class;
    protected $baseRouteName = 'iz_doelstellingen_';

    /**
     * @var BehandelaarDaoInterface
     *
     * @DI\Inject("iz.dao.doelstelling")
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
