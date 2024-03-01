<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use DagbestedingBundle\Entity\Contactpersoon;
use DagbestedingBundle\Form\ContactpersoonType;
use DagbestedingBundle\Service\ContactpersoonDaoInterface;
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
     * @var ContactpersoonDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(ContactpersoonDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }
}
