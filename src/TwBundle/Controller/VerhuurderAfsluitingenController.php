<?php

namespace TwBundle\Controller;

use TwBundle\Entity\VerhuurderAfsluiting;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/verhuurderafsluitingen")
 * @Template
 */
class VerhuurderAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen verhuurders';

    protected $entityClass = VerhuurderAfsluiting::class;

    protected $entityName = 'Afsluiting verhuurder';

    protected $indexRouteName = 'tw_verhuurderafsluitingen_index';
}
