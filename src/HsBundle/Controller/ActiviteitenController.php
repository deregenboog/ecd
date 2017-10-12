<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Activiteit;
use HsBundle\Form\ActiviteitType;
use HsBundle\Service\ActiviteitDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Controller\AbstractController;

/**
 * @Route("/admin/activiteiten")
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
     * @DI\Inject("hs.dao.activiteit")
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
