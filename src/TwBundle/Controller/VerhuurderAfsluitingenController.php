<?php

namespace TwBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use TwBundle\Entity\VerhuurderAfsluiting;
use TwBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/verhuurderafsluitingen")
 * @Template
 */
class VerhuurderAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen verhuurders';

    /**
     * @var AfsluitingDaoInterface
     *
     * @DI\Inject("TwBundle\Service\VerhuurderafsluitingDao")
     */
    protected $dao;

    protected $entityClass = VerhuurderAfsluiting::class;

    protected $entityName = 'Afsluiting verhuurder';

    protected $indexRouteName = 'tw_verhuurderafsluitingen_index';
}
