<?php

namespace TwBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Entity\VerhuurderAfsluiting;

/**
 * @Route("/admin/verhuurderafsluitingen")
 *
 * @Template
 */
class VerhuurderAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen verhuurders';

    protected $entityClass = VerhuurderAfsluiting::class;

    protected $entityName = 'Afsluiting verhuurder';

    protected $indexRouteName = 'tw_verhuurderafsluitingen_index';
}
