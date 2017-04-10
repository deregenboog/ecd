<?php

namespace OdpBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Entity\HuurverzoekAfsluiting;
use OdpBundle\Service\HuurverzoekAfsluitingDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/odp/admin/huurverzoekafsluitingen")
 */
class HuurverzoekAfsluitingenController extends AfsluitingenController
{
    /**
     * @var HuurverzoekAfsluitingDaoInterface
     *
     * @DI\Inject("odp.dao.huurverzoekafsluiting")
     */
    protected $dao;

    protected $entityClass = HuurverzoekAfsluiting::class;

    protected $entityName = 'Afsluiting huurverzoek';

    protected $indexRouteName = 'odp_huurverzoekafsluitingen_index';
}
