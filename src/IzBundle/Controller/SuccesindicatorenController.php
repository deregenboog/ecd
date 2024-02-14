<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Succesindicator;
use IzBundle\Form\SuccesindicatorType;
use IzBundle\Service\SuccesindicatorDao;
use IzBundle\Service\SuccesindicatorDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/succesindicatoren")
 */
class SuccesindicatorenController extends AbstractController
{
    protected $entityName = 'succesindicator';
    protected $entityClass = Succesindicator::class;
    protected $formClass = SuccesindicatorType::class;
    protected $baseRouteName = 'iz_succesindicatoren_';

    /**
     * @var SuccesindicatorDao
     */
    protected $dao;

    /**
     * @param SuccesindicatorDao $dao
     */
    public function __construct(SuccesindicatorDao $dao)
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
