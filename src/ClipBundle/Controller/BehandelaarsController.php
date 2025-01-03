<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Behandelaar;
use ClipBundle\Form\BehandelaarType;
use ClipBundle\Service\BehandelaarDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/behandelaars")
 *
 * @Template
 */
class BehandelaarsController extends AbstractController
{
    protected $title = 'Medewerkers';
    protected $entityName = 'medewerker';
    protected $entityClass = Behandelaar::class;
    protected $formClass = BehandelaarType::class;
    protected $baseRouteName = 'clip_behandelaars_';

    /**
     * @var BehandelaarDaoInterface
     */
    protected $dao;

    public function __construct(BehandelaarDaoInterface $dao)
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
