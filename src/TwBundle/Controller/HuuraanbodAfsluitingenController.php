<?php

namespace TwBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use TwBundle\Entity\HuuraanbodAfsluiting;
use TwBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/huuraanbodafsluitingen")
 * @Template
 */
class HuuraanbodAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen huuraanbiedingen';

    /**
     * @var AfsluitingDaoInterface
     *
     * @DI\Inject("TwBundle\Service\HuuraanbodafsluitingDao")
     */
    protected $dao;

    protected $entityClass = HuuraanbodAfsluiting::class;

    protected $entityName = 'Afsluiting huuraanbod';

    protected $indexRouteName = 'tw_huuraanbodafsluitingen_index';
}
