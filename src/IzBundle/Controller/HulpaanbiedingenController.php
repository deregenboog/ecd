<?php

namespace IzBundle\Controller;

use AppBundle\Export\AbstractExport;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Form\HulpaanbodFilterType;
use IzBundle\Service\HulpaanbodDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\AbstractChildController;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Form\HulpaanbodType;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Service\HulpvraagDaoInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        return $this->redirectToRoute('cake_iz_hulpaanbiedingen_view', [
            'iz_vrijwilliger_id' => $entity->getIzVrijwilliger()->getId(),
        ]);
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

        return $this->redirectToRoute('cake_iz_hulpaanbiedingen_view', ['iz_vrijwilliger_id' => $id]);
    }
}
