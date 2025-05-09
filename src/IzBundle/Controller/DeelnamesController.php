<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use Symfony\Component\Routing\Annotation\Route;
use IzBundle\Entity\Deelname;
use IzBundle\Form\DeelnameType;
use IzBundle\Service\DeelnameDaoInterface;

/**
 * @Route("/deelnames")
 */
class DeelnamesController extends AbstractChildController
{
    use DisableIndexActionTrait;

    protected $title = 'Trainingsdeelnames';
    protected $entityName = 'deelname';
    protected $entityClass = Deelname::class;
    protected $formClass = DeelnameType::class;
    protected $addMethod = 'addDeelname';
    protected $deleteMethod = 'removeDeelname';
    protected $baseRouteName = 'iz_deelname_';

    /**
     * @var DeelnameDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(DeelnameDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }
}
