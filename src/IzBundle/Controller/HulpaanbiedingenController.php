<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\AbstractExport;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Form\HulpaanbodCloseType;
use IzBundle\Form\HulpaanbodFilterType;
use IzBundle\Form\HulpaanbodType;
use IzBundle\Form\HulpvraagFilterType;
use IzBundle\Service\HulpaanbodDaoInterface;
use IzBundle\Service\HulpvraagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Filter\HulpvraagFilter;

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
    protected $forceRedirect = true;

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
        if ('iz_hulpaanbiedingen_view' === $request->get('_route')) {
            $form = $this->createForm(HulpvraagFilterType::class, new HulpvraagFilter(), [
                'enabled_filters' => [
                    'matching',
                    'startdatum',
                    'klant' => ['voornaam', 'achternaam', 'geslacht', 'geboortedatumRange', 'stadsdeel'],
                    'hulpvraagsoort',
                    'doelgroep',
                    'filter',
                ],
            ]);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form->getData()->matching) {
                    $kandidaten = $this->hulpvraagDao->findMatching($entity, $request->get('page', 1), $form->getData());
                } else {
                    $kandidaten = $this->hulpvraagDao->findAll($request->get('page', 1), $form->getData());
                }
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
