<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Verslaving;
use InloopBundle\Form\VerslavingType;
use InloopBundle\Service\VerslavingDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/verslavingen")
 * @Template
 */
class VerslavingenController extends AbstractController
{
    protected $title = 'Verslavingen';
    protected $entityName = 'verslaving';
    protected $entityClass = Verslaving::class;
    protected $formClass = VerslavingType::class;
    protected $baseRouteName = 'inloop_verslavingen_';

    /**
     * @var VerslavingDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("InloopBundle\Service\VerslavingDao");
    
        return $previous;
    }
}
