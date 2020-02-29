<?php

namespace PfoBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use PfoBundle\Entity\Verslag;
use PfoBundle\Form\VerslagType;
use PfoBundle\Service\VerslagDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/verslagen")
 */
class VerslagenController extends AbstractChildController
{
    protected $title = 'Verslagen';
    protected $entityName = 'verslag';
    protected $entityClass = Verslag::class;
    protected $formClass = VerslagType::class;
    protected $addMethod = 'addVerslag';
    protected $baseRouteName = 'pfo_verslagen_'; //JTB 20190724 hier stond oek_verslagen_ ??
    protected $disabledActions = ['deleted'];

    /**
     * @var VerslagDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct()
    {
        $this->dao = $this->get("PfoBundle\Service\VerslagDao");
        $this->entities = $this->get("pfo.verslag.entities");
    }
}
