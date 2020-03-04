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

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("OdpBundle\Service\HuurovereenkomstafsluitingDao");
    
        return $previous;
    }

    protected $entityClass = HuurovereenkomstAfsluiting::class;

    protected $entityName = 'Afsluiting koppeling';

    protected $indexRouteName = 'odp_huurovereenkomstafsluitingen_index';
}
