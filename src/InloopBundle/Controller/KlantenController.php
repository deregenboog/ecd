<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\AmocLand;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Land;
use AppBundle\Exception\UserException;
use AppBundle\Form\AppDateType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use InloopBundle\Entity\Aanmelding;
use InloopBundle\Entity\Afsluiting;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Registratie;
use InloopBundle\Entity\Schorsing;
use InloopBundle\Event\Events;
use InloopBundle\Form\AanmeldingType;
use InloopBundle\Form\AfsluitingType;
use InloopBundle\Form\KlantFilterType;
use InloopBundle\Form\KlantType;
use InloopBundle\Pdf\PdfBrief;
use InloopBundle\Service\KlantDao;
use InloopBundle\Service\KlantDaoInterface;
use InloopBundle\Service\LocatieDao;
use JMS\DiExtraBundle\Annotation as DI;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/klanten")
 * @Template
 */
class KlantenController extends AbstractController
{
    protected $entityName = 'klant';
    protected $entityClass = Klant::class;
    protected $formClass = KlantType::class;
    protected $filterFormClass = KlantFilterType::class;
    protected $baseRouteName = 'inloop_klanten_';

    /**
     * @var KlantDao
     */
    protected $dao;

    /**
     * @var \AppBundle\Service\KlantDao
     */
    protected $klantDao;

    /**
     * @var LocatieDao
     */
    protected $locatieDao;

    /**
     * @var array|mixed $tbc_countries List of countries where TBC check is mandatory
     */
    protected $tbc_countries=[];

    /**
     * @param KlantDao $dao
     * @param \AppBundle\Service\KlantDao $klantDao
     * @param array $tbc_countries
     */
    public function __construct(KlantDao $dao, \AppBundle\Service\KlantDao $klantDao, LocatieDao $locatieDao, ContainerInterface $container, $tbc_countries=[])
    {
        $this->dao = $dao;
        $this->klantDao = $klantDao;
        $this->container = $container;
        $this->tbc_countries = $tbc_countries;
        $this->locatieDao = $locatieDao;
    }

    /**
     * @Route("/{id}/view")
     * @Template
     */
    public function viewAction(Request $request, $id)
    {
        $response = parent::viewAction($request, $id);
        if(is_array($response)) $response['allRows'] = $this->locatieDao->findAllActiveLocationsOfTypeInloop();

        return $response;
    }

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        if ($request->get('klant')) {
            return $this->doAdd($request);
        }

        return $this->doSearch($request);
    }

    /**
     * @Route("/{klant}/rapportage")
     * @ParamConverter("klant", class="AppBundle\Entity\Klant")
     */
    public function viewReport(Request $request, Klant $klant)
    {
        $data = [];
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
                    'klant' => $klant,
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
                    'klant' => $klant,
                    'start_date' => $form->get('startdatum')->getData(),
                    'end_date' => $form->get('einddatum')->getData(),
                ])
                ->getQuery()
                ->getSingleScalarResult();

            return [
                'data' => $data,
                'startDate' => $form->get('startdatum')->getData(),
                'endDate' => $form->get('einddatum')->getData(),
                'klant' => $klant,
                'form' => $form->createView(),
            ];
        }

        return [
            'klant' => $klant,
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
            $klanten = $em->getRepository(Klant::class)->createQueryBuilder('klant')
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

            if ((is_array($klanten) || $klanten instanceof \Countable ? count($klanten) : 0) > 0) {
                foreach ($klanten as $klant) {
                    $data[$locatie->getNaam()][] = $klant;
                }
            }
        }

        return ['locaties' => $data];
    }

    /**
     * @Route("/{klant}/amoc.pdf")
     * @ParamConverter("klant", class="AppBundle\Entity\Klant")
     */
    public function amocAction(Klant $klant)
    {
        $html = $this->renderView('inloop/klanten/amoc_brief.pdf.twig', ['klant' => $klant]);
        $pdf = new PdfBrief($html);

        $response = new Response($pdf->Output(null, 'S'));
        $response->headers->set('Content-type', 'application/pdf');
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);
        $afsluiting = new Afsluiting($this->getMedewerker());
        $afsluiting->setKlant($klant);

        $form = $this->getForm(AfsluitingType::class, $afsluiting);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $klant->setHuidigeStatus($afsluiting);
                $entityManager->persist($afsluiting);
                $entityManager->flush();

                $this->eventDispatcher->dispatch(new GenericEvent($afsluiting), Events::DOSSIER_CHANGED);

                $this->addFlash('success', 'Inloopdossier is afgesloten');

            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if(array_key_exists("mwSluiten",$request->get('afsluiting')) )
            {
                if($klant->getHuidigeMwStatus() instanceof \MwBundle\Entity\Aanmelding)
                {
                    return $this->redirectToRoute("mw_klanten_close",["id"=>$id,"redirect"=>$this->generateUrl("inloop_klanten_index")]);
                }

            }
            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('inloop_klanten_index');
        }

        return [
            'klant' => $klant,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/open")
     */
    public function openAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);
        $aanmelding = new Aanmelding($this->getMedewerker());
        $aanmelding->setKlant($klant);

        if(in_array($klant->getLand()->getNaam(),$this->tbc_countries ) )
        {
            $this->addFlash("danger","Let op: klant uit risicoland. Doorverwijzen naar GGD voor TBC controle.");
        }

        $form = $this->getForm(AanmeldingType::class, $aanmelding);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $klant->setHuidigeStatus($aanmelding);
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

            return $this->redirectToRoute('inloop_klanten_index');
        }

        return [
            'klant' => $klant,
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
            'klant' => $entity,
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
    public function _schorsingenAction($id, $allRows = [])
    {
        $entity = $this->dao->find($id);

        return [
            'klant' => $entity,
            'allRows' => $allRows,
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
        return [
            'amoc_landen' => $this->getAmocLanden(),
            'tbc_countries' => $this->tbc_countries,

        ];
    }

    protected function getAmocLanden()
    {
        return $this->getEntityManager()->getRepository(Land::class)
            ->createQueryBuilder('land')
            ->innerJoin(AmocLand::class, 'amoc', 'WITH', 'amoc.land = land')
            ->getquery()
            ->getResult()
        ;
    }

    protected function doSearch(Request $request)
    {
        $filterForm = $this->getForm(AppKlantFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($request);

        $naam = "";
        if($filter = $request->get('klant_filter')) $naam = $filter["naam"];

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $count = (int) $this->klantDao->countAll($filterForm->getData());
            if (0 === $count) {
                $this->addFlash('info', sprintf('De zoekopdracht leverde geen resultaten op. Maak een nieuwe %s aan.', $this->entityName));

                return $this->redirectToRoute($this->baseRouteName.'add', ['klant' => 'new','naam'=>$naam ]);
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

    protected function doAdd(Request $request)
    {
        $tbc_countries = $this->tbc_countries;
        $tbc_countries_string = implode(", ",$tbc_countries);
        $klantId = $request->get('klant');
        if ('new' === $klantId) {
            $klant = new Klant($request);
        } else {
            $klant = $this->klantDao->find($klantId);
            if ($klant) {
                // redirect if already exists
                $inloopKlant = $this->dao->find($klantId);
                if ($inloopKlant->getHuidigeStatus()) {
                    return $this->redirectToView($inloopKlant);
                }
            }
        }

        $inloopKlant = $klant;
        $creationForm = $this->getForm(KlantType::class, $inloopKlant);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($inloopKlant);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');
                if(in_array($inloopKlant->getLand()->getNaam(),$tbc_countries))
                {
                    $this->addFlash("danger","Let op: klant uit risicoland. Doorverwijzen naar GGD voor TBC controle.");
                }

                return $this->redirectToView($inloopKlant);
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
            'entity' => $inloopKlant,
            'creationForm' => $creationForm->createView(),
            'amoc_landen' => $this->getAmocLanden(),
            'tbc_countries' => $tbc_countries,
        ];
    }
}
