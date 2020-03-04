<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Woonsituatie;
use InloopBundle\Form\WoonsituatieType;
use InloopBundle\Service\WoonsituatieDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/woonsituaties")
 * @Template
 */
class WoonsituatiesController extends AbstractController
{
    protected $title = 'Woonsituaties';
    protected $entityName = 'woonsituatie';
    protected $entityClass = Woonsituatie::class;
    protected $formClass = WoonsituatieType::class;
    protected $baseRouteName = 'inloop_woonsituaties_';

    /**
     * @var WoonsituatieDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("InloopBundle\Service\WoonsituatieDao");
    
        return $previous;
    }
}
