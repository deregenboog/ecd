<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use InloopBundle\Entity\Locatietijd;
use InloopBundle\Form\LocatietijdType;
use InloopBundle\Service\LocatietijdDao;
use InloopBundle\Service\LocatietijdDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/locatietijden")
 * @Template
 */
class LocatietijdenController extends AbstractChildController
{
    protected $title = 'Openingstijden';
    protected $entityName = 'openingstijd';
    protected $entityClass = Locatietijd::class;
    protected $formClass = LocatietijdType::class;
    protected $baseRouteName = 'inloop_locatietijden_';
    protected $addMethod = 'addLocatietijd';

    /**
     * @var LocatietijdDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @param LocatietijdDao $dao
     * @param \ArrayObject $entities
     */
    public function __construct(LocatietijdDao $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }


}
