<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use DagbestedingBundle\Entity\Contactpersoon;
use DagbestedingBundle\Form\ContactpersoonType;
use DagbestedingBundle\Service\ContactpersoonDao;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contactpersonen")
 */
class ContactpersonenController extends AbstractChildController
{
    protected $entityName = 'Contactpersoon';
    protected $entityClass = Contactpersoon::class;
    protected $formClass = ContactpersoonType::class;
    protected $addMethod = 'addContactpersoon';
    protected $baseRouteName = 'dagbesteding_contactpersonen_';

    /**
     * @var ContactpersoonDao
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @param ContactpersoonDao $dao
     * @param \ArrayObject $entities
     */
    public function __construct(ContactpersoonDao $dao,\ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }


}
