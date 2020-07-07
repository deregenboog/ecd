<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\ExportInterface;
use ClipBundle\Entity\Contactmoment;
use ClipBundle\Form\ContactmomentFilterType;
use ClipBundle\Form\ContactmomentType;
use ClipBundle\Service\ContactmomentDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/contactmomenten")
 * @Template
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
     * @DI\Inject("ClipBundle\Service\ContactmomentDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("clip.contactmoment.entities")
     */
    protected $entities;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("clip.export.contactmomenten")
     */
    protected $export;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        return $this->redirect(
            $this->generateUrl('clip_vragen_view', ['id' => $entity->getVraag()->getId(), '_fragment' => 'contactmomenten'])
        );
    }

    public function beforeCreate($entity){
        list($parentEntity,$parentDao) = $this->getParentConfig($this->getRequest());
        if($entity->getBehandelaar() !== null) $parentEntity->setBehandelaar($entity->getBehandelaar());
    }
}
