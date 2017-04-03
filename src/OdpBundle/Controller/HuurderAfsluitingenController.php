<?php

namespace OdpBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Entity\HuurderAfsluiting;
use OdpBundle\Service\HuurderAfsluitingDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/odp/admin/huurderafsluitingen")
 */
class HuurderAfsluitingenController extends AfsluitingenController
{
    /**
     * @var HuurderAfsluitingDaoInterface
     *
     * @DI\Inject("odp.dao.huurderafsluiting")
     */
    protected $dao;

    protected $entityClass = HuurderAfsluiting::class;

    protected $entityName = 'Afsluiting huurder';

    protected $indexRouteName = 'odp_huurderafsluitingen_index';
}
