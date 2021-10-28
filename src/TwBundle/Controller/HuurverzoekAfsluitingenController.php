<?php

namespace TwBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use TwBundle\Entity\HuurverzoekAfsluiting;
use TwBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/huurverzoekafsluitingen")
 * @Template
 */
class HuurverzoekAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen huurverzoeken';

    /**
     * @var AfsluitingDaoInterface
     *
     * @DI\Inject("TwBundle\Service\HuurverzoekafsluitingDao")
     */
    protected $dao;

    protected $entityClass = HuurverzoekAfsluiting::class;

    protected $entityName = 'Afsluiting huurverzoek';

    protected $indexRouteName = 'tw_huurverzoekafsluitingen_index';
}
