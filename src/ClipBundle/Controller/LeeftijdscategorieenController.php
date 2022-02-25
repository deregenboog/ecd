<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Leeftijdscategorie;
use ClipBundle\Form\LeeftijdscategorieType;
use ClipBundle\Service\LeeftijdscategorieDao;
use ClipBundle\Service\LeeftijdscategorieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/leeftijdscategorieen")
 */
class LeeftijdscategorieenController extends AbstractController
{
    protected $title = 'Leeftijdscategorieën';
    protected $entityName = 'leeftijdscategorie';
    protected $entityClass = Leeftijdscategorie::class;
    protected $formClass = LeeftijdscategorieType::class;
    protected $baseRouteName = 'clip_leeftijdscategorieen_';

    /**
     * @var LeeftijdscategorieDao
     */
    protected $dao;

    /**
     * @param LeeftijdscategorieDao $dao
     */
    public function __construct(LeeftijdscategorieDao $dao)
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
