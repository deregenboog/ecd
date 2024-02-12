<?php

namespace VillaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use VillaBundle\Entity\Deelname;

use VillaBundle\Form\DeelnameType;
use Symfony\Component\Routing\Annotation\Route;
use VillaBundle\Service\DeelnameDao;
use VillaBundle\Service\DeelnameDaoInterface;

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
    protected $baseRouteName = 'villa_deelname_';

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
