<?php

namespace OdpBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Entity\HuurovereenkomstAfsluiting;
use OdpBundle\Service\HuurovereenkomstAfsluitingDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/odp/admin/huurovereenkomstafsluitingen")
 */
class HuurovereenkomstAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen koppelingen';

    /**
     * @var HuurovereenkomstAfsluitingDaoInterface
     *
     * @DI\Inject("odp.dao.huurovereenkomstafsluiting")
     */
    protected $dao;

    protected $entityClass = HuurovereenkomstAfsluiting::class;

    protected $entityName = 'Afsluiting koppeling';

    protected $indexRouteName = 'odp_huurovereenkomstafsluitingen_index';
}
