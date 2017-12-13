<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use IzBundle\Entity\Hulpvraagsoort;
use IzBundle\Form\HulpvraagsoortType;
use IzBundle\Service\HulpvraagsoortDaoInterface;

/**
 * @Route("/admin/hulpvraagsoorten")
 */
class HulpvraagsoortenController extends AbstractController
{
    protected $title = 'Hulpvraagsoorten';
    protected $entityName = 'hulpvraagsoort';
    protected $entityClass = Hulpvraagsoort::class;
    protected $formClass = HulpvraagsoortType::class;
    protected $baseRouteName = 'iz_hulpvraagsoorten_';

    /**
     * @var HulpvraagsoortDaoInterface
     *
     * @DI\Inject("iz.dao.hulpvraagsoort")
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
