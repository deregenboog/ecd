<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
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
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/koppelingen")
 */
class KoppelingenController extends AbstractController
{
    protected $title = 'Koppelingen';
    protected $entityName = 'koppeling';
    protected $entityClass = Koppeling::class;
    protected $formClass = KoppelingType::class;
    protected $filterFormClass = KoppelingFilterType::class;
    protected $baseRouteName = 'iz_koppelingen_';
    protected $disabledActions = ['delete'];

    /**
     * @var KoppelingDaoInterface
     *
     * @DI\Inject("IzBundle\Service\KoppelingDao")
     */
    protected $dao;

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
        $hulpvraag = $this->dao->find($request->get('hulpvraag'));
        $hulpaanbod = $this->hulpaanbodDao->find($request->get('hulpaanbod'));

        $hulpvraag
            ->setHulpaanbod($hulpaanbod)
            ->setKoppelingStartdatum(new \DateTime())
        ;

        return $this->processForm($request, $hulpvraag);
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        if (!$entity->getKoppelingEinddatum()) {
            $entity->setKoppelingEinddatum(new \DateTime());
        }
        $this->formClass = KoppelingCloseType::class;

        return $this->processForm($request, $entity);
    }

    protected function afterFormSubmitted(Request $request, $entity)
    {
        return $this->redirectToView($entity);
    }
}
