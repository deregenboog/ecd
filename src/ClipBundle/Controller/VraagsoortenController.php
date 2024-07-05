<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Vraagsoort;
use ClipBundle\Form\VraagsoortType;
use ClipBundle\Service\VraagsoortDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     */
    protected $dao;

    public function __construct(VraagsoortDaoInterface $dao)
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
}
