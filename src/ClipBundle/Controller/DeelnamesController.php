<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use ClipBundle\Entity\Deelname;
use ClipBundle\Form\DeelnameType;
use ClipBundle\Service\DeelnameDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

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
    protected $baseRouteName = 'clip_deelname_';

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
