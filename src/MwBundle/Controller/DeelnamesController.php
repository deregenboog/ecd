<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use MwBundle\Entity\Deelname;

use MwBundle\Form\DeelnameType;
use MwBundle\Service\DeelnameDao;
use Symfony\Component\Routing\Annotation\Route;
use MwBundle\Service\DeelnameDaoInterface;

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
    protected $baseRouteName = 'mw_deelname_';

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
