<?php

namespace TwBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use TwBundle\Entity\KlantAfsluiting;
use TwBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/huurderafsluitingen")
 * @Template
 */
class HuurderAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen klanten';

    /**
     * @var AfsluitingDaoInterface
     *
     * @DI\Inject("TwBundle\Service\HuurderafsluitingDao")
     */
    protected $dao;

    protected $entityClass = KlantAfsluiting::class;

    protected $entityName = 'Afsluiting klant';

    protected $indexRouteName = 'tw_huurderafsluitingen_index';
}
