<?php

namespace OdpBundle\Controller;

use OdpBundle\Entity\HuurverzoekAfsluiting;
use OdpBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/huurverzoekafsluitingen")
 * @Template
 */
class HuurverzoekAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen huurverzoeken';

    /**
     * @var AfsluitingDaoInterface
     */
    protected $dao;

    public function __construct()
    {
        $this->dao = $this->get("OdpBundle\Service\HuurverzoekafsluitingDao");
    }

    protected $entityClass = HuurverzoekAfsluiting::class;

    protected $entityName = 'Afsluiting huurverzoek';

    protected $indexRouteName = 'odp_huurverzoekafsluitingen_index';
}
