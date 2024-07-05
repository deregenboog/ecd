<?php

namespace TwBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Entity\KlantAfsluiting;

/**
 * @Route("/admin/huurderafsluitingen")
 *
 * @Template
 */
class HuurderAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen klanten';

    protected $entityClass = KlantAfsluiting::class;

    protected $entityName = 'Afsluiting klant';

    protected $indexRouteName = 'tw_huurderafsluitingen_index';
}
