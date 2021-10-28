<?php

namespace TwBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use TwBundle\Entity\HuurovereenkomstAfsluiting;
use TwBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/huurovereenkomstafsluitingen")
 * @Template
 */
class HuurovereenkomstAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen koppelingen';

    /**
     * @var AfsluitingDaoInterface
     *
     * @DI\Inject("TwBundle\Service\HuurovereenkomstafsluitingDao")
     */
    protected $dao;

    protected $entityClass = HuurovereenkomstAfsluiting::class;

    protected $entityName = 'Afsluiting koppeling';

    protected $indexRouteName = 'tw_huurovereenkomstafsluitingen_index';
}
