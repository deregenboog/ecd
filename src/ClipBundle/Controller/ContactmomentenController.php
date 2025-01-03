<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\ExportInterface;
use ClipBundle\Entity\Contactmoment;
use ClipBundle\Form\ContactmomentFilterType;
use ClipBundle\Form\ContactmomentType;
use ClipBundle\Service\ContactmomentDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contactmomenten")
 *
 * @Template
 */
class ContactmomentenController extends AbstractChildController
{
    protected $entityName = 'contactmoment';
    protected $entityClass = Contactmoment::class;
    protected $formClass = ContactmomentType::class;
    protected $filterFormClass = ContactmomentFilterType::class;
    protected $addMethod = 'addContactmoment';
    protected $baseRouteName = 'clip_contactmomenten_';

    /**
     * @var ContactmomentDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct(ContactmomentDaoInterface $dao, \ArrayObject $entities, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->entities = $entities;
        $this->export = $export;
    }

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
}
