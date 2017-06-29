<?php

namespace ClipBundle\Controller;

use ClipBundle\Entity\Contactmoment;
use ClipBundle\Service\VerslagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\AbstractChildController;
use ClipBundle\Form\ContactmomentType;
use ClipBundle\Service\ContactmomentDaoInterface;
use ClipBundle\Form\ContactmomentFilterType;

/**
 * @Route("/contactmomenten")
 */
class ContactmomentenController extends AbstractChildController
{
    protected $title = 'Contactmomenten';
    protected $entityName = 'contactmoment';
    protected $entityClass = Contactmoment::class;
    protected $formClass = ContactmomentType::class;
    protected $filterFormClass = ContactmomentFilterType::class;
    protected $addMethod = 'addContactmoment';
    protected $baseRouteName = 'clip_contactmomenten_';

    /**
     * @var ContactmomentDaoInterface
     *
     * @DI\Inject("clip.dao.contactmoment")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("clip.contactmoment.entities")
     */
    protected $entities;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        $entity = $this->dao->find($id);

        return $this->redirect(
            $this->generateUrl('clip_vragen_view', ['id' => $entity->getVraag()->getId(), '_fragment' => 'contactmomenten'])
        );
    }
}
