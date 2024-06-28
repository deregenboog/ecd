<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\AbstractExport;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Filter\HulpvraagFilter;
use IzBundle\Form\HulpaanbodCloseType;
use IzBundle\Form\HulpaanbodFilterType;
use IzBundle\Form\HulpaanbodType;
use IzBundle\Form\HulpvraagFilterType;
use IzBundle\Service\HulpaanbodDaoInterface;
use IzBundle\Service\HulpvraagDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/hulpaanbiedingen")
 * @Template
 */
class HulpaanbiedingenController extends AbstractChildController
{
    protected $entityName = 'hulpaanbod';
    protected $entityClass = Hulpaanbod::class;
    protected $formClass = HulpaanbodType::class;
    protected $filterFormClass = HulpaanbodFilterType::class;
    protected $addMethod = 'addHulpaanbod';
    protected $baseRouteName = 'iz_hulpaanbiedingen_';
    protected $forceRedirect = true;

    /**
     * @var HulpaanbodDaoInterface
     */
    protected $dao;

    /**
     * @var HulpvraagDaoInterface
     */
    protected $hulpvraagDao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @var AbstractExport
     */
    protected $export;

    public function __construct(HulpaanbodDaoInterface $dao, HulpvraagDaoInterface $hulpvraagDao, \ArrayObject $entities, AbstractExport $export)
    {
        $this->dao = $dao;
        $this->hulpvraagDao = $hulpvraagDao;
        $this->entities = $entities;
        $this->export = $export;
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

    protected function addParams($entity, Request $request): array
    {
        if ('iz_hulpaanbiedingen_view' === $request->get('_route')) {
            $form = $this->getForm(HulpvraagFilterType::class, new HulpvraagFilter(), [
                'enabled_filters' => [
                    'matching',
                    'startdatum',
                    'klant' => ['voornaam', 'achternaam', 'geslacht', 'geboortedatumRange', 'stadsdeel'],
                    'hulpvraagsoort',
                    'doelgroep',
                    'zoekterm',
                    'filter',
                ],
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $kandidaten = $this->hulpvraagDao->findMatching($entity, $request->get('page', 1), $form->getData());
            } else {
                $kandidaten = $this->hulpvraagDao->findMatching($entity, $request->get('page', 1));
            }

            return [
                'filter' => $form->createView(),
                'kandidaten' => $kandidaten,
            ];
        }

        return [];
    }

    protected function redirectToView($entity)
    {
        if ($entity instanceof Hulpaanbod) {
            $vrijwilligerId = $entity->getIzVrijwilliger()->getId();
        } elseif ($entity instanceof IzVrijwilliger) {
            $vrijwilligerId = $entity->getId();
        }

        return $this->redirectToRoute('iz_vrijwilligers_view', ['id' => $vrijwilligerId, '_fragment' => 'koppelingen']);
    }
}
