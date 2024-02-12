<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Doelstelling;
use IzBundle\Form\DoelstellingFilterType;
use IzBundle\Form\DoelstellingType;
use IzBundle\Service\DoelstellingDao;
use IzBundle\Service\DoelstellingDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/doelstellingen")
 */
class DoelstellingenController extends AbstractController
{
    protected $entityName = 'doelstelling';
    protected $entityClass = Doelstelling::class;
    protected $formClass = DoelstellingType::class;
    protected $filterFormClass = DoelstellingFilterType::class;
    protected $baseRouteName = 'iz_doelstellingen_';

    /**
     * @var DoelstellingDao
     */
    protected $dao;

    /**
     * @param DoelstellingDao $dao
     */
    public function __construct(DoelstellingDao $dao)
    {
        $this->dao = $dao;
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
