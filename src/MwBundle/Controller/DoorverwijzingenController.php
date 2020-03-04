<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\Doorverwijzing;
use MwBundle\Form\DoorverwijzingType;
use MwBundle\Service\DoorverwijzingDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/doorverwijzingen")
 * @Template
 */
class DoorverwijzingenController extends AbstractController
{
    protected $title = 'Doorverwijzingen';
    protected $entityName = 'doorverwijzing';
    protected $entityClass = Doorverwijzing::class;
    protected $formClass = DoorverwijzingType::class;
    protected $baseRouteName = 'mw_doorverwijzingen_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var DoorverwijzingDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("MwBundle\Service\DoorverwijzingDao");
    
        return $previous;
    }
}
