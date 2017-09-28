<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Behandelaar;
use ClipBundle\Form\BehandelaarType;
use ClipBundle\Service\BehandelaarDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin/behandelaars")
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
     *
     * @DI\Inject("clip.dao.behandelaar")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        return $this->redirectToIndex();
    }

    protected function redirectToView($entity)
    {
        return $this->redirectToIndex();
    }
}
