<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Viacategorie;
use ClipBundle\Form\ViacategorieType;
use ClipBundle\Service\ViacategorieDao;
use ClipBundle\Service\ViacategorieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/viacategorieen")
 */
class ViacategorieenController extends AbstractController
{
    protected $title = 'Hoe bekend-opties';
    protected $entityName = 'hoe bekend-optie';
    protected $entityClass = Viacategorie::class;
    protected $formClass = ViacategorieType::class;
    protected $baseRouteName = 'clip_viacategorieen_';

    /**
     * @var ViacategorieDao
     */
    protected $dao;

    /**
     * @param ViacategorieDao $dao
     */
    public function __construct(ViacategorieDao $dao)
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
