<?php

namespace PfoBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use JMS\DiExtraBundle\Annotation as DI;
use AppBundle\Controller\AbstractController;
use PfoBundle\Entity\AardRelatie;
use PfoBundle\Form\AardRelatieType;

/**
 * @Route("/admin/aardrelaties")
 */
class AardRelatiesController extends AbstractController
{
    protected $title = 'Aard relaties';
    protected $entityName = 'aard relatie';
    protected $entityClass = AardRelatie::class;
    protected $formClass = AardRelatieType::class;
    protected $baseRouteName = 'pfo_aardrelaties_';
    protected $disabledActions = ['delete'];

    /**
     * @var AardRelatieDaoInterface
     *
     * @DI\Inject("PfoBundle\Service\AardRelatieDao")
     */
    protected $dao;
}
