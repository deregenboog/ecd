<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use IzBundle\Entity\Succesindicator;
use IzBundle\Form\SuccesindicatorType;
use IzBundle\Service\SuccesindicatoeDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/succesindicatoren")
 */
class SuccesindicatorenController extends AbstractController
{
    protected $title = 'Succesindicatoren';
    protected $entityName = 'succesindicator';
    protected $entityClass = Succesindicator::class;
    protected $formClass = SuccesindicatorType::class;
    protected $baseRouteName = 'iz_succesindicatoren_';

    /**
     * @var SuccesindicatorDaoInterface
     *
     * @DI\Inject("IzBundle\Service\SuccesindicatorDao")
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
