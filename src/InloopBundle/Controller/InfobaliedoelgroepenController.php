<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Infobaliedoelgroep;
use InloopBundle\Form\InfobaliedoelgroepType;
use InloopBundle\Service\InfobaliedoelgroepDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/infobaliedoelgroepen")
 * @Template
 */
class InfobaliedoelgroepenController extends AbstractController
{
    protected $title = 'Infobaliedoelgroepen';
    protected $entityName = 'infobaliedoelgroep';
    protected $entityClass = Infobaliedoelgroep::class;
    protected $formClass = InfobaliedoelgroepType::class;
    protected $baseRouteName = 'inloop_infobaliedoelgroepen_';

    /**
     * @var InfobaliedoelgroepDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("InloopBundle\Service\InfobaliedoelgroepDao");
    
        return $previous;
    }
}
