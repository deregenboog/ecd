<?php

namespace OdpBundle\Controller;

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
     */
    protected $dao;

    public function __construct()
    {
        $this->dao = $this->get("OdpBundle\Service\HuurderafsluitingDao");
    }

    protected $entityClass = HuurderAfsluiting::class;

    protected $entityName = 'Afsluiting huurder';

    protected $indexRouteName = 'odp_huurderafsluitingen_index';
}
