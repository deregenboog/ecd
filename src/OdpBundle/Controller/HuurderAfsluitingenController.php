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

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("OdpBundle\Service\HuurderafsluitingDao");
    
        return $previous;
    }

    protected $entityClass = HuurderAfsluiting::class;

    protected $entityName = 'Afsluiting huurder';

    protected $indexRouteName = 'odp_huurderafsluitingen_index';
}
