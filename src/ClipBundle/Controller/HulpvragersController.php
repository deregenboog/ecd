<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Hulpvrager;
use ClipBundle\Form\HulpvragerType;
use ClipBundle\Service\HulpvragerDao;
use ClipBundle\Service\HulpvragerDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/hulpvragers")
 * @Template
 */
class HulpvragersController extends AbstractController
{
    protected $title = 'Hulpvragers';
    protected $entityName = 'hulpvrager';
    protected $entityClass = Hulpvrager::class;
    protected $formClass = HulpvragerType::class;
    protected $baseRouteName = 'clip_hulpvragers_';

    /**
     * @var HulpvragerDao
     */
    protected $dao;

    /**
     * @param HulpvragerDao $dao
     */
    public function __construct(HulpvragerDao $dao)
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
