<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use TwBundle\Entity\Deelname;

use TwBundle\Form\DeelnameType;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Service\DeelnameDao;
use TwBundle\Service\DeelnameDaoInterface;

/**
 * @Route("/deelnames")
 */
class DeelnamesController extends AbstractChildController
{
    protected $title = 'Trainingsdeelnames';
    protected $entityName = 'deelname';
    protected $entityClass = Deelname::class;
    protected $formClass = DeelnameType::class;
    protected $addMethod = 'addDeelname';
    protected $deleteMethod = 'removeDeelname';
    protected $baseRouteName = 'tw_deelname_';

    /**
     * @var DeelnameDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @param DeelnameDao $dao
     * @param \ArrayObject $entities
     */
    public function __construct(DeelnameDao $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }


}
