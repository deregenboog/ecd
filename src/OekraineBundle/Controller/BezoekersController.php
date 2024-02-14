<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\AmocLand;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Entity\Land;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\AppDateType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use OekraineBundle\Entity\Aanmelding;
use OekraineBundle\Entity\Afsluiting;
use OekraineBundle\Entity\Bezoeker;
use OekraineBundle\Entity\Locatie;
use OekraineBundle\Entity\Registratie;
use OekraineBundle\Entity\Verslag;
use OekraineBundle\Event\Events;
use OekraineBundle\Export\BezoekerExport;
use OekraineBundle\Form\AanmeldingType;
use OekraineBundle\Form\AfsluitingType;
use OekraineBundle\Form\BezoekerFilterType;
use OekraineBundle\Form\BezoekerType;
use OekraineBundle\Pdf\PdfBrief;
use OekraineBundle\Service\BezoekerDao;
use OekraineBundle\Service\BezoekerDaoInterface;
use OekraineBundle\Service\KlantDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/bezoekers")
 * @Template
 */
class BezoekersController extends AbstractController
{
    use AccessProfileTrait;

    protected $entityName = 'bezoeker';
    protected $entityClass = Bezoeker::class;
    protected $formClass = BezoekerType::class;
    protected $filterFormClass = BezoekerFilterType::class;
    protected $baseRouteName = 'oekraine_bezoekers_';

    protected $addMethod = "addBezoeker";
    protected $searchFilterTypeClass = AppKlantFilterType::class;
    protected $searchEntity = AppKlant::class;
    protected $searchEntityName = 'appKlant';

    /**
     * @var BezoekerDao
     */
    protected $dao;

    /**
     * @var \AppBundle\Service\KlantDao
     */
    protected $bezoekerDao;

    /**
     * @var \AppBundle\Service\KlantDao
     */
    protected $searchDao;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @param BezoekerDao $dao
     * @param \AppBundle\Service\KlantDao $bezoekerDao
     * @param \AppBundle\Service\KlantDao $searchDao
     */
    public function __construct(BezoekerDao $dao, \AppBundle\Service\KlantDao $bezoekerDao, \AppBundle\Service\KlantDao $searchDao, BezoekerExport $export)
    {
        $this->dao = $dao;
        $this->klantDao = $bezoekerDao;
        $this->searchDao = $searchDao;
        $this->export = $export;
    }

    protected function doAdd(Request $request)
    {
        $entityId = $request->get('entity');

        if ('new' === $entityId) {
            $searchEntity = new $this->searchEntity();
        }
        else if($entityId == null)
        {
            $entity = new $this->entityClass();

            return $this->processForm($request, $entity);
        }
        else {
            $searchEntity = $this->searchDao->find($entityId);
            if ($searchEntity) {
                // redirect if already exists
                $subEntity = $this->dao->findOneBySearchEntity($searchEntity);
                if ($subEntity) {
                    return $this->redirectToView($subEntity);
                }
            }
        }

        $subEntity = new $this->entityClass($searchEntity);
        $creationForm = $this->getForm($this->formClass, $subEntity);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {

                $aanmelding = new Aanmelding($subEntity);
                $subEntity->setHuidigeStatus($aanmelding);


                $this->dao->create($subEntity);
                $this->addFlash('success', ucfirst($this->entityName) . ' is opgeslagen.');

                return $this->redirectToView($subEntity);
            } catch (UserException $e) {
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'entity' => $subEntity,
            'creationForm' => $creationForm->createView(),
        ];
    }

    /**
     * @Route("/{klant}/rapportage")
     * @ParamConverter("klant", class="OekraineBundle\Entity\Bezoeker")
     */
    public function viewReport(Request $request, Bezoeker $bezoeker)
    {
        $form = $this->createFormBuilder(null, ['method' => 'GET'])
            ->add('startdatum', AppDateType::class, [
                'required' => true,
                'data' => new \DateTime('first day of January this year'),
            ])
            ->add('einddatum', AppDateType::class, [
                'required' => true,
                'data' => (new \DateTime('today')),
            ])
            ->add('show', SubmitType::class, [
                'label' => 'Rapport tonen',
            ])
            ->getForm()
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $builder = $this->getEntityManager()->getRepository(Registratie::class)->createQueryBuilder('registratie')
                ->where('registratie.klant = :klant')
                ->andWhere('DATE(registratie.binnen) BETWEEN :start_date AND :end_date')
                ->setParameters([
                    'klant' => $bezoeker,
                    'start_date' => $form->get('startdatum')->getData(),
                    'end_date' => $form->get('einddatum')->getData(),
                ]);

            $data['bezoeken'] = (clone $builder)->select('COUNT(registratie.id)')->getQuery()->getSingleScalarResult();
            $data['douche'] = (clone $builder)->select('COUNT(registratie.id)')->andWhere('registratie.douche = true')->getQuery()->getSingleScalarResult();
            $data['kleding'] = (clone $builder)->select('COUNT(registratie.id)')->andWhere('registratie.kleding = true')->getQuery()->getSingleScalarResult();
            $data['maaltijd'] = (clone $builder)->select('COUNT(registratie.id)')->andWhere('registratie.maaltijd = true')->getQuery()->getSingleScalarResult();
            $data['activering'] = (clone $builder)->select('COUNT(registratie.id)')->andWhere('registratie.activering = true')->getQuery()->getSingleScalarResult();
            $data['bezoekenPerLocatie'] = (clone $builder)
                ->select('locatie.naam AS locatienaam, COUNT(registratie.id) AS aantal')
                ->innerJoin('registratie.locatie', 'locatie')
                ->groupBy('locatie.id')
                ->orderBy('aantal', 'DESC')
                ->getQuery()
                ->getScalarResult();

            $data['schorsingen'] = $this->getEntityManager()->getRepository(Schorsing::class)->createQueryBuilder('schorsing')
                ->select('COUNT(schorsing.id)')
                ->where('schorsing.klant = :klant')
                ->andWhere('schorsing.datumVan >= :start_date')
                ->andWhere('schorsing.datumTot <= :end_date')
                ->setParameters([
                    'klant' => $bezoeker,
                    'start_date' => $form->get('startdatum')->getData(),
                    'end_date' => $form->get('einddatum')->getData(),
                ])
                ->getQuery()
                ->getSingleScalarResult();

            return [
                'data' => $data,
                'startDate' => $form->get('startdatum')->getData(),
                'endDate' => $form->get('einddatum')->getData(),
                'klant' => $bezoeker,
                'form' => $form->createView(),
            ];
        }

        return [
            'klant' => $bezoeker,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/herintakes")
     */
    public function viewHerintakesAction()
    {
        $em = $this->getEntityManager();
        $locaties = $em->getRepository(Locatie::class)->findBy([], ['naam' => 'ASC']);

        $data = [];
        foreach ($locaties as $locatie) {
            $bezoekeren = $em->getRepository(Klant::class)->createQueryBuilder('klant')
                ->select('klant, intake')
                ->innerJoin('klant.laatsteIntake', 'intake', 'WITH', 'intake.intakedatum < :year_ago')
                ->innerJoin(Registratie::class, 'registratie', 'WITH', 'registratie.klant = klant')
                ->innerJoin('registratie.locatie', 'locatie', 'WITH', 'locatie = :locatie')
                ->where('registratie.binnen >= :month_ago')
                ->setParameters([
                    'month_ago' => new \DateTime('-1 month'),
                    'year_ago' => new \DateTime('-1 year'),
                    'locatie' => $locatie,
                ])
                ->getQuery()
                ->getResult()
            ;

            if (count($bezoekeren) > 0) {
                foreach ($bezoekeren as $bezoeker) {
                    $data[$locatie->getNaam()][] = $bezoeker;
                }
            }
        }

        return ['locaties' => $data];
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $bezoeker = $this->dao->find($id);
        $afsluiting = new Afsluiting($bezoeker, $this->getMedewerker());

        $form = $this->getForm(AfsluitingType::class, $afsluiting);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $entityManager->persist($afsluiting);
                $entityManager->flush();

                $this->eventDispatcher->dispatch(new GenericEvent($afsluiting), Events::DOSSIER_CHANGED);

                $this->addFlash('success', 'Oekrainedossier is afgesloten');

            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

//
            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('oekraine_bezoekers_index');
        }

        return [
            'klant' => $bezoeker,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/open")
     */
    public function openAction(Request $request, $id)
    {
        $bezoeker = $this->dao->find($id);
        $aanmelding = new Aanmelding($bezoeker, $this->getMedewerker());

        if(in_array($bezoeker->getAppKlant()->getLand()->getNaam(),$this->getParameter('tbc_countries') ) )
        {
            $this->addFlash("danger","Let op: klant uit risicoland. Doorverwijzen naar GGD voor TBC controle.");
        }

        $form = $this->getForm(AanmeldingType::class, $aanmelding);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $entityManager->persist($aanmelding);
                $entityManager->flush();


                $this->eventDispatcher->dispatch(new GenericEvent($aanmelding), Events::DOSSIER_CHANGED);

                $this->addFlash('success', 'Inloopdossier is heropend');
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('oekraine_bezoekers_index');
        }

        return [
            'klant' => $bezoeker,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Template
     */
    public function _intakesAction($id)
    {
        $entity = $this->dao->find($id);

        return [
            'bezoeker' => $entity,
        ];
    }

    /**
     * @Template
     */
    public function _opmerkingenAction($id)
    {
        $entity = $this->dao->find($id);

        return [
            'klant' => $entity,
        ];
    }

    /**
     * @Template
     */
    public function _schorsingenAction($id)
    {
        $entity = $this->dao->find($id);

        return [
            'klant' => $entity,
        ];
    }

    /**
     * @Template
     */
    public function _registratiesAction($id)
    {
        $entity = $this->dao->find($id);

        return [
            'klant' => $entity,
        ];
    }

    protected function addParams($entity, Request $request): array
    {
        $accessProfile = Verslag::ACCESS_INLOOP;

        return [
            'accessProfile' => $this->getAccessProfile(),
        ];
    }


    protected function doSearch(Request $request)
    {
        $filterForm = $this->getForm(AppKlantFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $count = (int) $this->klantDao->countAll($filterForm->getData());
            if (0 === $count) {
                $this->addFlash('info', sprintf('De zoekopdracht leverde geen resultaten op. Maak een nieuwe %s aan.', $this->entityName));

                return $this->redirectToRoute($this->baseRouteName.'add', ['entity' => 'new']);
            }

            if ($count > 100) {
                $filterForm->addError(new FormError('De zoekopdracht leverde teveel resultaten op. Probeer het opnieuw met een specifiekere zoekopdracht.'));
            }

            return [
                'filterForm' => $filterForm->createView(),
                'klanten' => $this->klantDao->findAll(null, $filterForm->getData()),
            ];
        }

        return [
            'filterForm' => $filterForm->createView(),
        ];
    }

    protected function DEPRECATE_REMOVE_MEdddoAdd(Request $request)
    {
        $bezoekerId = $request->get('klant');
        if ('new' === $bezoekerId) {
            $bezoeker = new Klant();
        } else {

            $bezoeker = $this->klantDao->find($bezoekerId);
            $bezoeker = $this->dao->findByKlantId($bezoekerId);
            if ($bezoeker) {
                // redirect if already exists
                if ($bezoeker->getHuidigeStatus()) {
                    return $this->redirectToView($bezoeker);
                }
            }
        }

        if(null == $bezoeker)
        {
            $bezoeker = new Bezoeker();
        }
        $bezoeker->setAppKlant($bezoeker);


        $creationForm = $this->getForm(BezoekerType::class, $bezoeker);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($bezoeker);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');
                return $this->redirectToView($bezoeker);
            } catch(UserException $e) {
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'entity' => $bezoeker,
            'creationForm' => $creationForm->createView(),
        ];
    }
}
