<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Leeftijdscategorie;
use ClipBundle\Form\LeeftijdscategorieType;
use ClipBundle\Service\LeeftijdscategorieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
     * @var LeeftijdscategorieDaoInterface
     *
     * @DI\Inject("clip.dao.leeftijdscategorie")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        return $this->redirectToIndex();
    }
}
