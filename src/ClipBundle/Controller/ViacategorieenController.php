<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ClipBundle\Service\ViacategorieDaoInterface;
use ClipBundle\Entity\Viacategorie;
use ClipBundle\Form\ViacategorieType;

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
     * @var ViacategorieDaoInterface
     *
     * @DI\Inject("clip.dao.viacategorie")
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
