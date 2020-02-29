<?php

namespace OdpBundle\Controller;

use OdpBundle\Entity\VerhuurderAfsluiting;
use OdpBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/verhuurderafsluitingen")
 * @Template
 */
class VerhuurderAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen verhuurders';

    /**
     * @var AfsluitingDaoInterface
     */
    protected $dao;

    protected $entityClass = VerhuurderAfsluiting::class;

    protected $entityName = 'Afsluiting verhuurder';

    protected $indexRouteName = 'odp_verhuurderafsluitingen_index';

    public function __construct()
    {
        $this->dao = $this->get("OdpBundle\Service\VerhuurderafsluitingDao");
    }
}
