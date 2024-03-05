<?php

namespace TwBundle\Controller;

use TwBundle\Entity\HuuraanbodAfsluiting;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/huuraanbodafsluitingen")
 * @Template
 */
class HuuraanbodAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen huuraanbiedingen';

    protected $entityClass = HuuraanbodAfsluiting::class;

    protected $entityName = 'Afsluiting huuraanbod';

    protected $indexRouteName = 'tw_huuraanbodafsluitingen_index';
}
