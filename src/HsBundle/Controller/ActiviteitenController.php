<?php

namespace HsBundle\Controller;

use AppBundle\Controller\AbstractController;
use HsBundle\Entity\Activiteit;
use HsBundle\Form\ActiviteitType;
use HsBundle\Service\ActiviteitDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/activiteiten")
 * @Template
 */
class ActiviteitenController extends AbstractController
{
    protected $title = 'Activiteiten';
    protected $entityName = 'activiteit';
    protected $entityClass = Activiteit::class;
    protected $formClass = ActiviteitType::class;
    protected $baseRouteName = 'hs_activiteiten_';

    /**
     * @var ActiviteitDaoInterface
     *
     * @DI\Inject("HsBundle\Service\ActiviteitDao")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
