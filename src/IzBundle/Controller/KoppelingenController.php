<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Export\AbstractExport;
use IzBundle\Entity\Koppeling;
use IzBundle\Form\KoppelingCloseType;
use IzBundle\Form\KoppelingFilterType;
use IzBundle\Form\KoppelingType;
use IzBundle\Service\HulpaanbodDaoInterface;
use IzBundle\Service\HulpvraagDaoInterface;
use IzBundle\Service\KoppelingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/koppelingen")
 * @Template
 */
class KoppelingenController extends AbstractChildController
{
    protected $title = 'Koppelingen';
    protected $entityName = 'koppeling';
    protected $entityClass = Koppeling::class;
    protected $formClass = KoppelingType::class;
    protected $filterFormClass = KoppelingFilterType::class;
    protected $addMethod = 'setKoppeling';
    protected $baseRouteName = 'iz_koppelingen_';
    protected $disabledActions = ['delete'];
    protected $forceRedirect = true;

    /**
     * @var KoppelingDaoInterface
     *
     * @DI\Inject("IzBundle\Service\KoppelingDao")
     */
    protected $dao;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("iz.koppeling.entities")
     */
    protected $entities;

    /**
     * @var HulpvraagDaoInterface
     *
     * @DI\Inject("IzBundle\Service\HulpvraagDao")
     */
    protected $hulpvraagDao;

    /**
     * @var HulpaanbodDaoInterface
     *
     * @DI\Inject("IzBundle\Service\HulpaanbodDao")
     */
    protected $hulpaanbodDao;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("iz.export.koppelingen")
     */
    protected $export;

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $hulpvraag = $this->hulpvraagDao->find($request->get('hulpvraag'));
        $hulpaanbod = $this->hulpaanbodDao->find($request->get('hulpaanbod'));

        $koppeling = new Koppeling($hulpvraag, $hulpaanbod);

        return $this->processForm($request, $koppeling);
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        if (!$entity->getAfsluitdatum()) {
            $entity->setAfsluitdatum(new \DateTime());
        }
        $this->formClass = KoppelingCloseType::class;

        return $this->processForm($request, $entity);
    }
}
