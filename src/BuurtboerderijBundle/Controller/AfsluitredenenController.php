<?php

namespace BuurtboerderijBundle\Controller;

use AppBundle\Controller\AbstractController;
use BuurtboerderijBundle\Entity\Afsluitreden;
use BuurtboerderijBundle\Form\AfsluitredenType;
use BuurtboerderijBundle\Service\AfsluitredenDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/afsluitredenen")
 */
class AfsluitredenenController extends AbstractController
{
    protected $title = 'Afsluitredenen';
    protected $entityName = 'afsluitreden';
    protected $entityClass = Afsluitreden::class;
    protected $formClass = AfsluitredenType::class;
    protected $baseRouteName = 'buurtboerderij_afsluitredenen_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var AfsluitredenDaoInterface
     */
    protected $dao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        parent::setContainer($container);

        $this->dao = $this->get("BuurtboerderijBundle\Service\AfsluitredenDao");
    
        return $container;
    }
}
