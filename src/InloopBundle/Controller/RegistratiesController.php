<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Controller\DisableIndexActionTrait;
use AppBundle\Entity\Klant;
use AppBundle\Export\ExportInterface;
use AppBundle\Service\ECDHelper;
use Doctrine\ORM\EntityNotFoundException;
use InloopBundle\Entity\Aanmelding;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\LocatieType;
use InloopBundle\Entity\Registratie;
use InloopBundle\Event\Events;
use InloopBundle\Filter\KlantFilter;
use InloopBundle\Filter\RegistratieFilter;
use InloopBundle\Filter\RegistratieHistoryFilter;
use InloopBundle\Form\KlantFilterType;
use InloopBundle\Form\RegistratieFilterType;
use InloopBundle\Form\RegistratieHistoryFilterType;
use InloopBundle\Form\RegistratieType;
use InloopBundle\Security\Permissions;
use InloopBundle\Service\KlantDaoInterface;
use InloopBundle\Service\LocatieDaoInterface;
use InloopBundle\Service\RegistratieDaoInterface;
use InloopBundle\Service\SchorsingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/registraties")
 *
 * @Template
 */
class RegistratiesController extends AbstractController
{
    use DisableIndexActionTrait;

    protected $title = 'Bezoekersregistratie';
    protected $entityName = 'registratie';
    protected $entityClass = Registratie::class;
    protected $formClass = RegistratieType::class;
    protected $baseRouteName = 'inloop_registraties_';

    /**
     * @var RegistratieDaoInterface
     */
    protected $dao;

    /**
     * @var KlantDaoInterface
     */
    protected $klantDao;

    /**
     * @var LocatieDaoInterface
     */
    protected $locatieDao;

    /**
     * @var SchorsingDaoInterface
     */
    protected $schorsingDao;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct(RegistratieDaoInterface $dao, KlantDaoInterface $klantDao, LocatieDaoInterface $locatieDao, SchorsingDaoInterface $schorsingDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->klantDao = $klantDao;
        $this->locatieDao = $locatieDao;
        $this->schorsingDao = $schorsingDao;
        $this->export = $export;
    }

    /**
     * @Route("/locatieSelect/{locatieType}", requirements={"locatieType" = "\S+"})
     *
     * @ParamConverter("locatieType", class="InloopBundle\Entity\LocatieType", options={"mapping": {"locatieType"="naam"}})
     */
    public function locationSelectAction(Request $request, LocatieType $locatieType)
    {
        $locatieTypeStr = (string) $locatieType;

        $builder = $this->getEntityManager()->getRepository(Locatie::class)
            ->createQueryBuilder('locatie')
            ->leftJoin('locatie.locatieTypes', 'locatieTypes')
            ->where('locatie.maatschappelijkWerk = false')
            ->andWhere('locatie.datumVan <= DATE(CURRENT_TIMESTAMP())')
            ->andWhere('locatie.datumTot > DATE(CURRENT_TIMESTAMP()) OR locatie.datumTot IS NULL')
            ->andWhere('locatieTypes.naam IN(:naam)')
            ->orderBy('locatie.naam')
            ->setParameter('naam', $locatieTypeStr)
        ;

        $locaties = $builder
            ->getQuery()
            ->getResult()
        ;

        return [
            'locaties' => $locaties,
            'locatieType' => $locatieTypeStr,
        ];
    }

    /**
     * @Route("/klanten/{locatieType}/{locatie}", name="inloop_registraties_klanten", requirements={"locatie" = "\d+", "locatieType"="\S+"})
     *
     * @ParamConverter("locatie", class="InloopBundle\Entity\Locatie")
     * @ParamConverter("locatieType",class="InloopBundle\Entity\LocatieType",options={"mapping": {"locatieType"="naam"}})
     */
    public function klantenAction(Request $request)
    {
        $locatie = $request->get('locatie');
        $locatieType = $request->get('locatieType');

        $this->denyAccessUnlessGranted(Permissions::REGISTER, $locatie);

        $filter = new KlantFilter();
        $filter->locatie = $locatie;
        $filter->huidigeStatus = Aanmelding::class;
        $filter->nuBinnen = false;

        $form = $this->getForm(KlantFilterType::class, $filter, [
            'attr' => ['class' => 'ajaxFilter'],
            'enabled_filters' => [
                'klant' => ['id', 'voornaam', 'achternaam', 'geboortedatum', 'geslacht'],
                'gebruikersruimte',
                'laatsteIntakeLocatie',
                'filter',
            ],
        ]);
        $form->handleRequest($request);

        if (!$request->isXmlHttpRequest()) {
            return [
                'locatie' => $locatie,
                'filter' => $form->createView(),
                'locatieType' => $locatieType,
            ];
        }

        $this->getEntityManager()->getFilters()->enable('overleden');

        $page = $request->get('page', 1);
        $pagination = $this->klantDao->findAll($page, $filter);

        return $this->render('inloop/registraties/_klanten.html.twig', [
            'locatie' => $locatie,
            'filter' => $form->createView(),
            'pagination' => $pagination,
            'locatieType' => $locatieType,
        ]);
    }

    /**
     * Route("/active/{locatie}").
     *
     * @Route("/active/{locatieType}/{locatie}", name="inloop_registraties_active", requirements={"locatie" = "\d+", "locatieType"="\S+"})
     *
     * @ParamConverter("locatie", class="InloopBundle\Entity\Locatie")
     * @ParamConverter("locatieType",class="InloopBundle\Entity\LocatieType",options={"mapping": {"locatieType"="naam"}})
     */
    public function activeAction(Request $request, Locatie $locatie)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $locatie);

        $locatieType = $request->get('locatieType');

        $filter = new RegistratieFilter($locatie);

        $form = $this->getForm(RegistratieFilterType::class, $filter, [
            'attr' => ['class' => 'ajaxFilter'],
        ]);
        $form->handleRequest($request);

        if (!$request->isXmlHttpRequest()) {
            return [
                'locatie' => $locatie,
                'filter' => $form->createView(),
                'locatieType' => $locatieType,
            ];
        }

        $page = $request->get('page', 1);
        $pagination = $this->dao->findActive($page, $filter);

        $klantIds = array_map(function (Registratie $registratie) {
            return $registratie->getKlant()->getId();
        }, $pagination->getItems());
        $event = new GenericEvent($klantIds, ['geen_activering_klant_ids' => []]);
        $this->eventDispatcher->dispatch($event, Events::GEEN_ACTIVERING);

        return $this->render('inloop/registraties/_active.html.twig', [
            'locatie' => $locatie,
            'locatieType' => $locatieType,
            'filter' => isset($form) ? $form->createView() : null,
            'pagination' => $pagination,
            'geen_activering_klant_ids' => $event->getArgument('geen_activering_klant_ids'),
        ]);
    }

    /**
     * Route("/history/{locatie}").
     *
     * @Route("/history/{locatieType}/{locatie}", name="inloop_registraties_history", requirements={"locatie" = "\d+", "locatieType"="\S+"})
     *
     * @ParamConverter("locatie", class="InloopBundle\Entity\Locatie")
     * @ParamConverter("locatieType",class="InloopBundle\Entity\LocatieType",options={"mapping": {"locatieType"="naam"}})
     */
    public function historyAction(Request $request, Locatie $locatie)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $locatie);

        $locatieType = $request->get('locatieType');

        $filter = new RegistratieHistoryFilter($locatie);
        $form = $this->getForm(RegistratieHistoryFilterType::class, $filter, [
            'attr' => ['class' => 'ajaxFilter'],
        ]);
        $form->handleRequest($request);

        if (!$request->isXmlHttpRequest()) {
            return [
                'locatie' => $locatie,
                'filter' => $form->createView(),
                'locatieType' => $locatieType,
            ];
        }

        $page = $request->get('page', 1);
        $pagination = $this->dao->findHistory($page, $filter);

        $klantIds = array_map(function (Registratie $registratie) {
            return $registratie->getKlant()->getId();
        }, $pagination->getItems());
        $event = new GenericEvent($klantIds, ['geen_activering_klant_ids' => []]);
        $this->eventDispatcher->dispatch($event, Events::GEEN_ACTIVERING);

        return $this->render('inloop/registraties/_history.html.twig', [
            'locatie' => $locatie,
            'locatieType' => $locatieType,
            'filter' => isset($form) ? $form->createView() : null,
            'pagination' => $pagination,
            'geen_activering_klant_ids' => $event->getArgument('geen_activering_klant_ids'),
        ]);
    }

    /**
     * This logic is copied from original code base. Candidate for refactoring.
     *
     * @todo
     *
     * @Route("/jsonCanRegister/{klant}/{locatie}")
     */
    public function jsonCanRegisterAction(Klant $klant, Locatie $locatie, ECDHelper $ECDHelper, $h = 1)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $locatie);

        $jsonVar = [
            'confirm' => false,
            'allow' => true,
            'message' => '',
        ];

        $sep = '';
        $separator = PHP_EOL.PHP_EOL;


        /*
         * Voor gebruikersruimte geldt dat klant minimaal twee mnd geleden ingecheckt moet zijn op de ruimte zoals
         * staat ingesteld in de toegang (obv eerste intake).
         * Tevens moet de intake jonger dan 2 mnd zijn.
         */
        if ($locatie->isGebruikersruimte()) {
            $laatsteRegistratie = $this->dao->findLatestByKlantAndLocatie($klant, $locatie);
            if ($laatsteRegistratie instanceof Registratie
                && $laatsteRegistratie->getBinnen() < new \DateTime('-2 months')
                && ($locatie->getId() != $klant->getEersteIntake()->getGebruikersruimte()->getId()
                    || $klant->getLaatsteIntake()->getIntakedatum() < new \DateTime('-2 months')
                )
            ) {
                $jsonVar['allow'] = false;
                $jsonVar['message'] .= 'Langer dan twee maanden niet geweest. Opnieuw aanmelden via het maatschappelijk werk.';

                return new JsonResponse($jsonVar);
            }
        }

        $open = $locatie->isOpen();
        $open = true; // vanwege niet goed te traceren fouten (openingstijden kloppen, toch zegt ie gesloten) dit nu zo gedaan.

        if (true !== $open) {
            $jsonVar['allow'] = false;
            $jsonVar['message'] = 'Deze locatie is nog niet open, klant kan nog niet inchecken!';
            //            $jsonVar['debug'] = $open;
            //
            $jsonVar['message'] .= "\n\n".$open['message'];

            return new JsonResponse($jsonVar);
        }

        try {
            if ($klant->getLaatsteRegistratie()) {
                if (!$klant->getLaatsteRegistratie()->getBuiten()) {
                    if ($klant->getLaatsteRegistratie()->getLocatie() == $locatie) {
                        $jsonVar['allow'] = false;
                        $jsonVar['message'] .= $separator.'Deze klant is op dit moment al ingecheckt op deze locatie.';
                    } else {
                        $jsonVar['confirm'] = true;
                        $jsonVar['message'] .= $separator.'Deze klant is op dit moment al ingecheckt op een andere locatie. Toch inchecken?';
                        
                    }

                    
                } else {
                    $diff = $klant->getLaatsteRegistratie()->getBuiten()->diff(new \DateTime());

                    if ($diff->h < $h && 0 == $diff->d && 0 == $diff->m && 0 == $diff->y) {
                        $jsonVar['confirm'] = true;
                        $jsonVar['message'] .= $separator.'Deze klant is minder dan een uur geleden uitgechecked. Opnieuw registreren?';
                        
                    }
                }
            }
        } catch (EntityNotFoundException $e) {
            // laatste registratie is gearchiveerd, dus niet recent
        }

        if ($jsonVar['allow']) {
            if (($laatsteIntake = $klant->getLaatsteIntake()) == null) {
                $jsonVar['message'] .= $separator.'Let op: deze persoon heeft geen intake. Toch inchecken?';
                $jsonVar['confirm'] = true;
            }

            if ((($laatsteRegistratie = $klant->getLaatsteRegistratie()) !== null)
                && null !== $laatsteRegistratie->getBuiten()
                && $laatsteRegistratie->getBuiten()->diff(new \DateTime())->days > 730
                && $klant->getLaatsteIntake()->getIntakedatum()->diff(new \DateTime())->days > 365
            ) {
                $jsonVar['message'] .= $separator.'Let op: deze persoon heeft zich al twee jaar nergens meer geregistreerd en heeft een nieuwe intake nodig. Toch inchecken?';

                $jsonVar['confirm'] = true;
            }

            $actieveSchorsingen = $this->schorsingDao->findActiefByKlantAndLocatie($klant, $locatie);
            if ((is_array($actieveSchorsingen) || $actieveSchorsingen instanceof \Countable ? count(
                    $actieveSchorsingen
                ) : 0) > 0) {
                $alleLocaties = $this->locatieDao->findAllActiveLocationsOfTypeInloop();
                $schorsingsLocaties = [];
                foreach ($actieveSchorsingen as $schorsing) {
                    $schorsingsLocaties = array_merge($schorsing->getLocaties()->toArray(), $schorsingsLocaties);
                }
                $l = $ECDHelper->filterAllRows($schorsingsLocaties, $alleLocaties);

                $tmpMsg = $jsonVar['message'];
                $jsonVar['message'] = '!! Let op: deze persoon is momenteel op deze locatie(s) geschorst: '.$l.'.  Toch inchecken?';
                $jsonVar['message'] .= $separator.$tmpMsg;

                $jsonVar['confirm'] = true;
            }

            $terugkeergesprekNodig = $this->schorsingDao->findTerugkeergesprekNodigByKlantAndLocatie($klant, $locatie);
            if ((is_array($terugkeergesprekNodig) || $terugkeergesprekNodig instanceof \Countable ? count(
                    $terugkeergesprekNodig
                ) : 0) > 0) {
                $jsonVar['message'] .= $separator.'Let op: deze persoon is 14 dagen of langer geschorst geweest en heeft een terugkeergesprek nodig.';
                $jsonVar['confirm'] = true;
            }

            if ((is_array($klant->getOpenstaandeOpmerkingen()) || $klant->getOpenstaandeOpmerkingen(
                ) instanceof \Countable ? count($klant->getOpenstaandeOpmerkingen()) : 0) > 0) {
                $opmerkingen = $klant->getOpenstaandeOpmerkingen()->toArray();
                foreach ($opmerkingen as $opmerking) {
                    $jsonVar['message'] .= $separator.'Openstaande opmerking ('.$opmerking->getCreated()->format(
                            'd-m-Y'
                        ).'): '.$opmerking->getBeschrijving();

                }
                $jsonVar['confirm'] = true;
            }

            if ($klant->isJarigVandaag())
            {
                $jsonVar['confirm'] = true;
                $jsonVar['message'] .= $separator.'!! Hiep hiep hoera !! Let op: klant is jarig vandaag!';
            }
        }
        $jsonVar['message'] = trim($jsonVar['message'],PHP_EOL);
        return new JsonResponse($jsonVar);
    }

    /**
     * @Route("/registratieCheckOut/{registratie}")
     */
    public function registratieCheckOutAction(Request $request, Registratie $registratie)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $registratie->getLocatie());

        $this->dao->checkout($registratie);

        return new JsonResponse();
    }

    /**
     * @Route("/checkoutAll/{locatie}")
     */
    public function checkoutAllAction(Request $request, Locatie $locatie)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $locatie);

        $filter = new RegistratieFilter($locatie);
        $registraties = $this->dao->findActive(null, $filter);

        foreach ($registraties as $registratie) {
            $this->dao->checkout($registratie);
        }

        return new JsonResponse();
    }

    /**
     * @Route("/{registratie}/delete")
     *
     * @ParamConverter("registratie", class="InloopBundle\Entity\Registratie")
     */
    public function deleteAction(Request $request, $registratie)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $registratie->getLocatie());

        $this->dao->delete($registratie);

        return new JsonResponse();
    }

    /**
     * @Route("/ajaxAddRegistratie/{klant}/{locatie}")
     */
    public function ajaxAddRegistratieAction(Request $request, Klant $klant, Locatie $locatie)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $locatie);

        /*
         * Wanneer een klant ergens incheckt, wordt hij eerst overal uitgecheckt.
         */
        $this->dao->checkoutKlantFromAllLocations($klant);
        $registratie = $this->dao->create(new Registratie($klant, $locatie));
        $klant->setLaatsteRegistratie($registratie);
        $this->klantDao->update($klant);

        return new JsonResponse();
    }

    /**
     * @Route("/{registratie}/douche/add")
     */
    public function doucheAddAction(Request $request, Registratie $registratie)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $registratie->getLocatie());

        // set position in queue
        $queue = $this->dao->findShowerQueue($registratie->getLocatie());
        $registratie->setDouche(1 + (is_array($queue) || $queue instanceof \Countable ? count($queue) : 0));
        $this->dao->update($registratie);

        return new JsonResponse(['douche' => $registratie->getDouche()]);
    }

    /**
     * @Route("/{registratie}/douche/del")
     */
    public function doucheDelAction(Request $request, Registratie $registratie)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $registratie->getLocatie());

        if ($registratie->getDouche() > 0) {
            $registratie->setDouche(-1);
        } else {
            $registratie->setDouche(0);
        }
        $this->dao->update($registratie);
        $this->dao->reorderShowerQueue($registratie->getLocatie());

        return new JsonResponse(['douche' => $registratie->getDouche()]);
    }

    /**
     * @Route("/{registratie}/mw/add")
     */
    public function mwAddAction(Request $request, Registratie $registratie)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $registratie->getLocatie());

        // set position in queue
        $queue = $this->dao->findMwQueue($registratie->getLocatie());
        $registratie->setMw(1 + (is_array($queue) || $queue instanceof \Countable ? count($queue) : 0));
        $this->dao->update($registratie);

        return new JsonResponse(['mw' => $registratie->getMw()]);
    }

    /**
     * @Route("/{registratie}/mw/del")
     */
    public function mwDelAction(Request $request, Registratie $registratie)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $registratie->getLocatie());

        if ($registratie->getMw() > 0) {
            $registratie->setMw(-1);
        } else {
            $registratie->setMw(0);
        }
        $this->dao->update($registratie);
        $this->dao->reorderMwQueue($registratie->getLocatie());

        return new JsonResponse(['mw' => $registratie->getMw()]);
    }

    /**
     * @Route("/{registratie}/kleding/{value}")
     */
    public function updateKledingAction(Request $request, Registratie $registratie, $value)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $registratie->getLocatie());

        $registratie->setKleding((int) $value);
        $this->dao->update($registratie);

        return new JsonResponse(['kleding' => $registratie->isKleding()]);
    }

    /**
     * @Route("/{registratie}/maaltijd/{value}")
     */
    public function updateMaaltijdAction(Request $request, Registratie $registratie, $value)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $registratie->getLocatie());

        $registratie->setMaaltijd((int) $value);
        $this->dao->update($registratie);

        return new JsonResponse(['maaltijd' => $registratie->isMaaltijd()]);
    }

    /**
     * @Route("/{registratie}/activering/{value}")
     */
    public function updateActiveringAction(Request $request, Registratie $registratie, $value)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $registratie->getLocatie());

        $registratie->setActivering((int) $value);
        $this->dao->update($registratie);

        return new JsonResponse(['activering' => $registratie->isActivering()]);
    }

    /**
     * Because `douche` and `mw` are queues, TRUE is stored as -1 and FALSE is
     * stored as 0. Queue positions are stored as positive integers.
     *
     * @Route("/{registratie}/douche/{value}")
     */
    public function updateDoucheAction(Request $request, Registratie $registratie, $value)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $registratie->getLocatie());

        $registratie->setDouche(-1 * $value);
        $this->dao->update($registratie);

        return new JsonResponse(['douche' => -1 * $registratie->getDouche()]);
    }

    /**
     * Because `douche` and `mw` are queues, TRUE is stored as -1 and FALSE is
     * stored as 0. Queue positions are stored as positive integers.
     *
     * @Route("/{registratie}/mw/{value}")
     */
    public function updateMwAction(Request $request, Registratie $registratie, $value)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $registratie->getLocatie());

        $registratie->setMw(-1 * $value);
        $this->dao->update($registratie);

        return new JsonResponse(['mw' => -1 * $registratie->getMw()]);
    }

    /**
     * @Route("/{registratie}/veegploeg/{value}")
     */
    public function updateVeegploegAction(Request $request, Registratie $registratie, $value)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $registratie->getLocatie());

        $registratie->setVeegploeg((int) $value);
        $this->dao->update($registratie);

        return new JsonResponse(['veegploeg' => $registratie->isVeegploeg()]);
    }

    /**
     * @Route("/{registratie}/aantalSpuiten/{value}")
     */
    public function updateAantalSpuitenAction(Request $request, Registratie $registratie, $value)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $registratie->getLocatie());

        $registratie->setAantalSpuiten((int) $value);
        $this->dao->update($registratie);

        return new JsonResponse(['aantalSpuiten' => $registratie->getAantalSpuiten()]);
    }
}
