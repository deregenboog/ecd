<?php

namespace OdpBundle\Controller;

use OdpBundle\Entity\HuuraanbodAfsluiting;
use OdpBundle\Service\AfsluitingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/huuraanbodafsluitingen")
 * @Template
 */
class HuuraanbodAfsluitingenController extends AfsluitingenController
{
    public $title = 'Afsluitingen huuraanbiedingen';

    /**
     * @var AfsluitingDaoInterface
     */
    protected $dao;

    public function __construct()
    {
        $this->dao = $this->get("OdpBundle\Service\HuuraanbodafsluitingDao");
    }

    protected $entityClass = HuuraanbodAfsluiting::class;

    protected $entityName = 'Afsluiting huuraanbod';

    protected $indexRouteName = 'odp_huuraanbodafsluitingen_index';
}
