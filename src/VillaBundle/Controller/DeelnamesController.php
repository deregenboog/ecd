<?php

namespace VillaBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use VillaBundle\Entity\Deelname;

use VillaBundle\Form\DeelnameType;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
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
     * @var DeelnameDaoInterface
     *
     * @DI\Inject("VillaBundle\Service\DeelnameDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("villa.deelname.entities")
     */
    protected $entities;
}
