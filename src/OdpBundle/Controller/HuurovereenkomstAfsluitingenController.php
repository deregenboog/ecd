<?php

namespace OdpBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Entity\HuurovereenkomstAfsluiting;
use OdpBundle\Service\AfsluitingDaoInterface;
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
     * @DI\Inject("OdpBundle\Service\HuurovereenkomstafsluitingDao")
     */
    protected $dao;

    protected $entityClass = HuurovereenkomstAfsluiting::class;

    protected $entityName = 'Afsluiting koppeling';

    protected $indexRouteName = 'odp_huurovereenkomstafsluitingen_index';
}
