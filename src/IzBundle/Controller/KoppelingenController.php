<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Exception\AppException;
use AppBundle\Exception\UserException;
use AppBundle\Export\AbstractExport;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\ConfirmationType;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Entity\Koppeling;
use IzBundle\Form\KoppelingCloseType;
use IzBundle\Form\KoppelingFilterType;
use IzBundle\Form\KoppelingType;
use IzBundle\Service\HulpaanbodDao;
use IzBundle\Service\HulpaanbodDaoInterface;
use IzBundle\Service\HulpvraagDao;
use IzBundle\Service\HulpvraagDaoInterface;
use IzBundle\Service\KoppelingDao;
use IzBundle\Service\KoppelingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/koppelingen")
 * @Template
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
     * @var KoppelingDao
     */
    protected $dao;

    /**
     * @var HulpvraagDao
     */
    protected $hulpvraagDao;

    /**
     * @var HulpaanbodDao
     */
    protected $hulpaanbodDao;

    /**
     * @var AbstractExport
     */
    protected $export;

    /**
     * @param KoppelingDao $dao
     * @param HulpvraagDao $hulpvraagDao
     * @param HulpaanbodDao $hulpaanbodDao
     * @param AbstractExport $export
     */
    public function __construct(KoppelingDao $dao, HulpvraagDao $hulpvraagDao, HulpaanbodDao $hulpaanbodDao, AbstractExport $export)
    {
        $this->dao = $dao;
        $this->hulpvraagDao = $hulpvraagDao;
        $this->hulpaanbodDao = $hulpaanbodDao;
        $this->export = $export;
    }


    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $hulpvraag = $this->dao->find($request->get('hulpvraag'));
        $hulpaanbod = $this->hulpaanbodDao->find($request->get('hulpaanbod'));

        try {
            $hulpvraag->setHulpaanbod($hulpaanbod);
        } catch (UserException $exception) {
            $this->addFlash('danger', $exception->getMessage());

            return $this->redirectToRoute('iz_hulpvragen_view', ['id' => $hulpvraag->getId()]);
        }

        $hulpvraag->getKoppeling()
            ->setStartdatum(new \DateTime())
            ->setMedewerker($this->getMedewerker())
        ;

        return $this->processForm($request, $hulpvraag);
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        $this->formClass = KoppelingCloseType::class;

        return $this->processForm($request, $entity);
    }

    /**
     * @Route("/{id}/reopen")
     */
    public function reopenAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entity->getKoppeling()->reopen();
                $this->dao->update($entity);

                $this->addFlash('success', ucfirst($this->entityName).' is heropend.');
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    protected function afterFormSubmitted(Request $request, $entity, $form = null)
    {
        return $this->redirectToView($entity);
    }

//     protected function download(FilterInterface $filter)
//     {
//         if (!$this->export) {
//             throw new AppException(get_class($this).'::export not set!');
//         }

//         ini_set('memory_limit', '512M');

//         $filename = $this->getDownloadFilename();
//         $hulpvragen = $this->dao->findAll(null, $filter);

//         $converter = function(Hulpvraag $hulpvraag) {
//             return new Koppeling($hulpvraag, $hulpvraag->getHulpaanbod());
//         };
//         $koppelingen = array_map($converter, $hulpvragen);

//         return $this->export->create($koppelingen)->getResponse($filename);
//     }
}
