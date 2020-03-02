<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use DagbestedingBundle\Entity\Verslag;
use DagbestedingBundle\Form\VerslagType;
use DagbestedingBundle\Service\VerslagDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/verslagen")
 */
class VerslagenController extends AbstractChildController
{
    protected $title = 'Verslagen';
    protected $entityName = 'Verslag';
    protected $entityClass = Verslag::class;
    protected $formClass = VerslagType::class;
    protected $addMethod = 'addVerslag';
    protected $baseRouteName = 'dagbesteding_verslagen_';

    /**
     * @var VerslagDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        parent::setContainer($container);

        $this->dao = $this->get("DagbestedingBundle\Service\VerslagDao");
        $this->entities = $this->get("dagbesteding.verslag.entities");
    
        return $container;
    }
}
