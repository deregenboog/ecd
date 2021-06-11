<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\AbstractController;
use OdpBundle\Entity\Locatie;
use OdpBundle\Form\LocatieType;
use OdpBundle\Service\LocatieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/locaties")
 * @Template
 */
class LocatiesController extends AbstractController
{
    protected $title = 'Locaties';
    protected $entityName = 'locatie';
    protected $entityClass = Locatie::class;
    protected $formClass = LocatieType::class;
    protected $baseRouteName = 'odp_locaties_';

    /**
     * @var LocatieDaoInterface
     *
     * @DI\Inject("OdpBundle\Service\LocatieDao")
     */
    protected $dao;
}
