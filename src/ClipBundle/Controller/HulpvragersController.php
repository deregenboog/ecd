<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Hulpvrager;
use ClipBundle\Form\HulpvragerType;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ClipBundle\Service\HulpvragerDaoInterface;

/**
 * @Route("/admin/hulpvragers")
 */
class HulpvragersController extends AbstractController
{
    protected $title = 'Hulpvragers';
    protected $entityName = 'hulpvrager';
    protected $entityClass = Hulpvrager::class;
    protected $formClass = HulpvragerType::class;
    protected $baseRouteName = 'clip_hulpvragers_';

    /**
     * @var HulpvragerDaoInterface
     *
     * @DI\Inject("clip.dao.hulpvrager")
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
