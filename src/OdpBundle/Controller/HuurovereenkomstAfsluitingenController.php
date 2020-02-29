<?php

namespace OdpBundle\Controller;

use OdpBundle\Entity\HuurovereenkomstAfsluiting;
use OdpBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/huurovereenkomstafsluitingen")
 * @Template
 */
class HuurovereenkomstAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen koppelingen';

    /**
     * @var AfsluitingDaoInterface
     */
    protected $dao;

    public function __construct()
    {
        $this->dao = $this->get("OdpBundle\Service\HuurovereenkomstafsluitingDao");
    }

    protected $entityClass = HuurovereenkomstAfsluiting::class;

    protected $entityName = 'Afsluiting koppeling';

    protected $indexRouteName = 'odp_huurovereenkomstafsluitingen_index';
}
