<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use InloopBundle\Entity\Locatietijd;
use InloopBundle\Form\LocatietijdType;
use InloopBundle\Service\LocatietijdDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/locatietijden")
 *
 * @Template
 */
class LocatietijdenController extends AbstractChildController
{
    use DisableIndexActionTrait;

    protected $title = 'Openingstijden';
    protected $entityName = 'openingstijd';
    protected $entityClass = Locatietijd::class;
    protected $formClass = LocatietijdType::class;
    protected $baseRouteName = 'inloop_locatietijden_';
    protected $addMethod = 'addLocatietijd';

    /**
     * @var LocatietijdDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(LocatietijdDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }
}
