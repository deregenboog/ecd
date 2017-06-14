<?php

namespace DagbestedingBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\AbstractChildController;
use DagbestedingBundle\Form\ContactpersoonType;
use DagbestedingBundle\Entity\Contactpersoon;

/**
 * @Route("/contactpersonen")
 */
class ContactpersonenController extends AbstractChildController
{
    protected $title = 'Contactpersonen';
    protected $entityName = 'Contactpersoon';
    protected $entityClass = Contactpersoon::class;
    protected $formClass = ContactpersoonType::class;
    protected $addMethod = 'addContactpersoon';
    protected $baseRouteName = 'dagbesteding_contactpersonen_';

    /**
     * @var ContactpersoonDaoInterface
     *
     * @DI\Inject("dagbesteding.dao.contactpersoon")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("dagbesteding.contactpersoon.entities")
     */
    protected $entities;
}
