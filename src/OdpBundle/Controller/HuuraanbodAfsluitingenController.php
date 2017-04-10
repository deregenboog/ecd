<?php

namespace OdpBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Entity\HuuraanbodAfsluiting;
use OdpBundle\Service\HuuraanbodAfsluitingDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/odp/admin/huuraanbodafsluitingen")
 */
class HuuraanbodAfsluitingenController extends AfsluitingenController
{
    /**
     * @var HuuraanbodAfsluitingDaoInterface
     *
     * @DI\Inject("odp.dao.huuraanbodafsluiting")
     */
    protected $dao;

    protected $entityClass = HuuraanbodAfsluiting::class;

    protected $entityName = 'Afsluiting huuraanbod';

    protected $indexRouteName = 'odp_huuraanbodafsluitingen_index';
}
