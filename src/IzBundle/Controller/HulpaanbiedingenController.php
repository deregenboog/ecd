<?php

namespace IzBundle\Controller;

use AppBundle\Export\AbstractExport;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Form\HulpaanbodType;
use IzBundle\Form\HulpaanbodFilterType;
use IzBundle\Service\HulpaanbodDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\AbstractChildController;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Service\HulpvraagDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Form\HulpaanbodConnectType;
use IzBundle\Form\HulpaanbodCloseType;

/**
 * @Route("/hulpaanbiedingen")
 */
class HulpaanbiedingenController extends AbstractChildController
{
    protected $title = 'Hulpaanbiedingen';
    protected $entityName = 'hulpaanbod';
    protected $entityClass = Hulpaanbod::class;
    protected $formClass = HulpaanbodType::class;
    protected $filterFormClass = HulpaanbodFilterType::class;
    protected $addMethod = 'addHulpaanbod';
    protected $baseRouteName = 'iz_hulpaanbiedingen_';

    /**
     * @var HulpaanbodDaoInterface
     *
     * @DI\Inject("IzBundle\Service\HulpaanbodDao")
     */
    protected $dao;

    /**
     * @var HulpvraagDaoInterface
     *
     * @DI\Inject("IzBundle\Service\HulpvraagDao")
     */
    protected $hulpvraagDao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("iz.hulpaanbod.entities")
     */
    protected $entities;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("iz.export.hulpaanbiedingen")
     */
    protected $export;

    /**
     * @Route("/{id}/connect")
     */
    public function connectAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        $entity->setKoppelingStartdatum(new \DateTime());

        $this->formClass = HulpaanbodConnectType::class;

        return $this->processForm($request, $entity);
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        $this->formClass = HulpaanbodCloseType::class;

        return $this->processForm($request, $entity);
    }

    protected function addParams($entity, Request $request)
    {
        return [
            'kandidaten' => $this->hulpvraagDao->findMatching($entity, $request->get('page', 1)),
        ];
    }

    protected function redirectToView($entity)
    {
        if ($entity instanceof Hulpaanbod) {
            $id = $entity->getIzVrijwilliger()->getId();
        } elseif ($entity instanceof IzVrijwilliger) {
            $id = $entity->getId();
        }

        return $this->redirectToRoute('iz_hulpaanbiedingen_view', ['id' => $id]);
    }
}
