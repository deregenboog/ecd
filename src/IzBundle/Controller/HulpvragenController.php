<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\AbstractExport;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Form\HulpvraagType;
use IzBundle\Form\HulpvraagFilterType;
use IzBundle\Service\HulpvraagDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Form\HulpvraagConnectType;
use IzBundle\Form\HulpvraagCloseType;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Entity\IzKlant;
use IzBundle\Service\HulpaanbodDaoInterface;

/**
 * @Route("/hulpvragen")
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

    /**
     * @var HulpvraagDaoInterface
     *
     * @DI\Inject("IzBundle\Service\HulpvraagDao")
     */
    protected $dao;

    /**
     * @var HulpaanbodDaoInterface
     *
     * @DI\Inject("IzBundle\Service\HulpaanbodDao")
     */
    protected $hulpaanbodDao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("iz.hulpvraag.entities")
     */
    protected $entities;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("iz.export.hulpvragen")
     */
    protected $export;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        return $this->redirectToRoute('cake_iz_hulpvragen_view', [
            'iz_klant_id' => $entity->getIzKlant()->getId(),
        ]);
    }

    /**
     * @Route("/{id}/connect")
     */
    public function connectAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        $this->formClass = HulpvraagConnectType::class;

        return $this->processForm($request, $entity);
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
        return [
            'kandidaten' => $this->hulpaanbodDao->findMatching($entity, $request->get('page', 1)),
        ];
    }

    protected function redirectToView($entity)
    {
        if ($entity instanceof Hulpvraag) {
            $id = $entity->getIzKlant()->getId();
        } elseif ($entity instanceof IzKlant) {
            $id = $entity->getId();
        }

        return $this->redirectToRoute('cake_iz_hulpvragen_view', ['iz_klant_id' => $id]);
    }
}
