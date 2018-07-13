<?php

namespace OdpBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Entity\HuuraanbodAfsluiting;
use OdpBundle\Service\AfsluitingDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/huuraanbodafsluitingen")
 */
class HuuraanbodAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen huuraanbiedingen';

    /**
     * @var AfsluitingDaoInterface
     *
     * @DI\Inject("odp.dao.huuraanbodafsluiting")
     */
    protected $dao;

    protected $entityClass = HuuraanbodAfsluiting::class;

    protected $entityName = 'Afsluiting huuraanbod';

    protected $indexRouteName = 'odp_huuraanbodafsluitingen_index';
}
