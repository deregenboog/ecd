<?php

namespace OdpBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Entity\HuurverzoekAfsluiting;
use OdpBundle\Service\AfsluitingDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/huurverzoekafsluitingen")
 */
class HuurverzoekAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen huurverzoeken';

    /**
     * @var AfsluitingDaoInterface
     *
     * @DI\Inject("odp.dao.huurverzoekafsluiting")
     */
    protected $dao;

    protected $entityClass = HuurverzoekAfsluiting::class;

    protected $entityName = 'Afsluiting huurverzoek';

    protected $indexRouteName = 'odp_huurverzoekafsluitingen_index';
}
