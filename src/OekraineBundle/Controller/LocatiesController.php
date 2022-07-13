<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractController;
use OekraineBundle\Entity\Locatie;
use OekraineBundle\Form\LocatieType;
use OekraineBundle\Service\LocatieDaoInterface;
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
    protected $baseRouteName = 'oekraine_locaties_';

    /**
     * @var LocatieDaoInterface
     *
     * @DI\Inject("OekraineBundle\Service\LocatieDao")
     */
    protected $dao;
}
