<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Entity\VormVanOvereenkomst;
use TwBundle\Form\VormVanOvereenkomstType;
use TwBundle\Service\VormVanOvereenkomstDaoInterface;

/**
 * @Route("/admin/vormvanovereenkomst")
 */
class VormVanOvereenkomstController extends AbstractController
{
    protected $entityName = 'vormvanovereenkomst';
    protected $entityClass = VormVanOvereenkomst::class;
    protected $formClass = VormVanOvereenkomstType::class;
    protected $baseRouteName = 'tw_vormvanovereenkomst_';
    protected $title = 'Vorm van overeenkomst';

    /**
     * @var VormVanOvereenkomstDaoInterface
     */
    protected $dao;

    public function __construct(VormVanOvereenkomstDaoInterface $dao)
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
