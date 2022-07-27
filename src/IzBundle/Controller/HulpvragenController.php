<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\AbstractExport;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\IzKlant;
use IzBundle\Filter\HulpaanbodFilter;
use IzBundle\Form\HulpaanbodFilterType;
use IzBundle\Form\HulpvraagCloseType;
use IzBundle\Form\HulpvraagFilterType;
use IzBundle\Form\HulpvraagType;
use IzBundle\Service\HulpaanbodDao;
use IzBundle\Service\HulpaanbodDaoInterface;
use IzBundle\Service\HulpvraagDao;
use IzBundle\Service\HulpvraagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/hulpvragen")
 * @Template
 */
class HulpvragenController extends AbstractChildController
{
    protected $title = 'Hulpvragen';
    protected $entityName = 'hulpvraag';
    protected $entityClass = Hulpvraag::class;
    protected $formClass = HulpvraagType::class;
    protected $filterFormClass = HulpvraagFilterType::class;
    protected $baseRouteName = 'iz_hulpvragen_';
    protected $disabledActions = ['delete'];
    protected $addMethod = 'addHulpvraag';
    protected $forceRedirect = true;

    /**
     * @var HulpvraagDao
     */
    protected $dao;

    /**
     * @var HulpaanbodDao
     */
    protected $hulpaanbodDao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @var AbstractExport
     */
    protected $export;

    /**
     * @param HulpvraagDao $dao
     * @param HulpaanbodDao $hulpaanbodDao
     * @param \ArrayObject $entities
     * @param AbstractExport $export
     */
    public function __construct(HulpvraagDao $dao, HulpaanbodDao $hulpaanbodDao, \ArrayObject $entities, AbstractExport $export)
    {
        $this->dao = $dao;
        $this->hulpaanbodDao = $hulpaanbodDao;
        $this->entities = $entities;
        $this->export = $export;
    }


    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        $this->formClass = HulpvraagCloseType::class;

        return $this->processForm($request, $entity);
    }

    protected function addParams($entity, Request $request)
    {
        if ('iz_hulpvragen_view' === $request->get('_route')) {
            $form = $this->getForm(HulpaanbodFilterType::class, new HulpaanbodFilter(), [
                'enabled_filters' => [
                    'matching',
                    'startdatum',
                    'vrijwilliger' => ['voornaam', 'achternaam', 'geslacht', 'geboortedatumRange', 'stadsdeel'],
                    'hulpvraagsoort',
                    'doelgroep',
                    'filter',
                ],
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $kandidaten = $this->hulpaanbodDao->findMatching($entity, $request->get('page', 1), $form->getData());
            } else {
                $kandidaten = $this->hulpaanbodDao->findMatching($entity, $request->get('page', 1));
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
        if ($entity instanceof Hulpvraag) {
            $klantId = $entity->getIzKlant()->getId();
        } elseif ($entity instanceof IzKlant) {
            $klantId = $entity->getId();
        }

        return $this->redirectToRoute('iz_klanten_view', ['id' => $klantId, '_fragment' => 'koppelingen']);
    }
}
