<?php

namespace PfoBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use PfoBundle\Entity\AardRelatie;
use PfoBundle\Form\AardRelatieType;
use Symfony\Component\Routing\Annotation\Route;

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
