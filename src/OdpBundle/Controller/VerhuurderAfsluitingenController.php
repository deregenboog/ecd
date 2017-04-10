<?php

namespace OdpBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Entity\VerhuurderAfsluiting;
use OdpBundle\Service\VerhuurderAfsluitingDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/odp/admin/verhuurderafsluitingen")
 */
class VerhuurderAfsluitingenController extends AfsluitingenController
{
    /**
     * @var VerhuurderAfsluitingDaoInterface
     *
     * @DI\Inject("odp.dao.verhuurderafsluiting")
     */
    protected $dao;

    protected $entityClass = VerhuurderAfsluiting::class;

    protected $entityName = 'Afsluiting verhuurder';

    protected $indexRouteName = 'odp_verhuurderafsluitingen_index';
}
