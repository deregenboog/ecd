<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\ExportInterface;
use ClipBundle\Entity\Contactmoment;
use ClipBundle\Form\ContactmomentFilterType;
use ClipBundle\Form\ContactmomentType;
use ClipBundle\Service\ContactmomentDaoInterface;
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

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("ClipBundle\Service\ContactmomentDao");
        $this->entities = $container->get("clip.contactmoment.entities");
        $this->export = $container->get("clip.export.contactmomenten");
    
        return $previous;
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
