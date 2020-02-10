<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Vraagsoort;
use ClipBundle\Form\VraagsoortType;
use ClipBundle\Service\VraagsoortDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/vraagsoorten")
 */
class VraagsoortenController extends AbstractController
{
    protected $title = 'Onderwerpen';
    protected $entityName = 'onderwerp';
    protected $entityClass = Vraagsoort::class;
    protected $formClass = VraagsoortType::class;
    protected $baseRouteName = 'clip_vraagsoorten_';

    /**
     * @var VraagsoortDaoInterface
     *
     * @DI\Inject("ClipBundle\Service\VraagsoortDao")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
