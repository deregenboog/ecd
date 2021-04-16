<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Locatie;
use ClipBundle\Form\LocatieType;
use ClipBundle\Service\LocatieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/locaties")
 * @Template
 */
class LocatiesController extends AbstractController
{
    protected $title = 'Locaties';
    protected $entityName = 'locatie';
    protected $entityClass = Locatie::class;
    protected $formClass = LocatieType::class;
    protected $baseRouteName = 'clip_locaties_';

    /**
     * @var LocatieDaoInterface
     *
     * @DI\Inject("ClipBundle\Service\LocatieDao")
     */
    protected $dao;
}
