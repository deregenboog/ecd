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

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("OdpBundle\Service\HuurverzoekafsluitingDao");
    
        return $previous;
    }

    protected $entityClass = HuurverzoekAfsluiting::class;

    protected $entityName = 'Afsluiting huurverzoek';

    protected $indexRouteName = 'odp_huurverzoekafsluitingen_index';
}
