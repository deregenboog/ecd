<?php

namespace OdpBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Entity\HuurderAfsluiting;
use OdpBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/huurderafsluitingen")
 * @Template
 */
class HuurderAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen huurders';

    /**
     * @var AfsluitingDaoInterface
     *
     * @DI\Inject("odp.dao.huurderafsluiting")
     */
    protected $dao;

    protected $entityClass = HuurderAfsluiting::class;

    protected $entityName = 'Afsluiting huurder';

    protected $indexRouteName = 'odp_huurderafsluitingen_index';
}
