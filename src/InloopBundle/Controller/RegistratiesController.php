<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Export\ExportInterface;
use AppBundle\Service\KlantDaoInterface;
use DagbestedingBundle\Service\LocatieDaoInterface;
use Doctrine\ORM\EntityNotFoundException;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Registratie;
use InloopBundle\Filter\KlantFilter;
use InloopBundle\Form\KlantFilterType;
use InloopBundle\Form\RegistratieFilterType;
use InloopBundle\Form\RegistratieType;
use InloopBundle\Service\RegistratieDaoInterface;
use InloopBundle\Service\SchorsingDaoInterface;
use InloopBundle\Strategy\AmocStrategy;
use InloopBundle\Strategy\GebruikersruimteStrategy;
use InloopBundle\Strategy\VerblijfsstatusStrategy;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use InloopBundle\Filter\RegistratieFilter;
use InloopBundle\Filter\RegistratieHistoryFilter;
use InloopBundle\Form\RegistratieHistoryFilterType;

/**
 * @Route("/registraties")
 */
class RegistratiesController extends AbstractController
{
    protected $title = 'Bezoekersregistratie';
    protected $entityName = 'registratie';
    protected $entityClass = Registratie::class;
    protected $formClass = RegistratieType::class;
    protected $filterFormClass = RegistratieFilterType::class;
    protected $baseRouteName = 'inloop_registraties_';

    /**
     * @var RegistratieDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\RegistratieDao")
     */
    protected $dao;

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("AppBundle\Service\KlantDao")
     */
    protected $klantDao;

    /**
     * @var LocatieDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\LocatieDao")
     */
    protected $locatieDao;

    /**
     * @var SchorsingDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\SchorsingDao")
     */
    protected $schorsingDao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("inloop.export.registraties")
     */
    protected $export;

    /**
     * @Route("/")
     */
    public function locationSelectAction(Request $request)
    {
        $locaties = $this->getEntityManager()->getRepository(Locatie::class)
            ->createQueryBuilder('locatie')
            ->where('locatie.maatschappelijkWerk = false')
            ->andWhere('locatie.datumVan <= :today')
            ->andWhere('locatie.datumTot > :today OR locatie.datumTot = :null')
            ->orderBy('locatie.naam')
            ->setParameter('today', new \DateTime('today'))
            ->setParameter('null', '0000-00-00') // @todo replace 0000-00-00 with null
            ->getQuery()
            ->getResult()
        ;

        return [
            'locaties' => $locaties,
        ];
    }

    /**
     * @Route("/{locatie}", requirements={"locatie" = "\d+"})
     */
    public function indexAction(Request $request, Locatie $locatie)
    {
        // @todo move to container service
        $strategies = [
            new GebruikersruimteStrategy(),
            new AmocStrategy(),
            new VerblijfsstatusStrategy(),
        ];

        foreach ($strategies as $strategy) {
            if ($strategy->supports($locatie)) {
                $filter = new KlantFilter($strategy);
                break;
            }
        }

        if (!isset($filter)) {
            $filter = new KlantFilter();
        }

        $form = $this->createForm(KlantFilterType::class, $filter, [
            'enabled_filters' => [
                'klant' => ['id', 'naam', 'geboortedatum', 'geslacht'],
                'gebruikersruimte',
                'laatsteIntakeLocatie',
                'filter',
            ],
        ]);
        $form->handleRequest($request);

        $this->getEntityManager()->getFilters()->enable('overleden');

        $page = $request->get('page', 1);
        $pagination = $this->klantDao->findAll($page, $filter);

        return [
            'locatie' => $locatie,
            'filter' => $form->createView(),
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/jsonCanRegister/{klant}/{locatie}")
     */
    public function jsonCanRegisterAction(Klant $klant, Locatie $locatie, $h = 1)
    {
        $jsonVar = [
            'confirm' => false,
            'allow' => true,
            'message' => '',
        ];

        $sep = '';
        $separator = PHP_EOL.PHP_EOL;

        if ($locatie->isGebruikersruimte()) {
            $laatsteRegistratie = $this->dao->findLatestByKlantAndLocatie($klant, $locatie);
            if ($laatsteRegistratie instanceof Registratie
                && $laatsteRegistratie->getBinnen() < new \DateTime('-2 months')
                && ($locatie->getId() != $klant->getLaatsteIntake()->getGebruikersruimte()->getId()
                    || $klant->getLaatsteIntake()->getIntakedatum() < new \DateTime('-2 months')
                )
            ) {
                $jsonVar['allow'] = false;
                $jsonVar['message'] = 'Langer dan twee maanden niet geweest. Opnieuw aanmelden via het maatschappelijk werk.';

                return new JsonResponse($jsonVar);
            }
        }

        if ($locatie->isGebruikersruimte()
            && $klant->getLaatsteIntake()->isMagGebruiken()
            && !$klant->getLaatsteTbcControle()
        ) {
            $jsonVar['allow'] = false;
            $jsonVar['message'] = 'Deze klant heeft geen TBC controle gehad en kan niet worden ingecheckt bij een locatie met een gebruikersruimte.';

            return new JsonResponse($jsonVar);
        }

        if (!$locatie->isOpen()) {
            $jsonVar['allow'] = false;
            $jsonVar['message'] = 'Deze locatie is nog niet open, klant kan nog niet inchecken!';

            return new JsonResponse($jsonVar);
        }

        try {
            if ($klant->getLaatsteRegistratie()) {
                if (!$klant->getLaatsteRegistratie()->getBuiten()) {
                    if ($klant->getLaatsteRegistratie()->getLocatie() == $locatie) {
                        $jsonVar['allow'] = false;
                        $jsonVar['message'] .= $sep.'Deze klant is op dit moment al ingecheckt op deze locatie.';
                    } else {
                        $jsonVar['confirm'] = true;
                        $jsonVar['message'] .= $sep.'Deze klant is op dit moment al ingecheckt op een andere locatie. Toch inchecken?';
                        $sep = $separator;
                    }

                    $sep = $separator;
                } else {
                    $diff = $klant->getLaatsteRegistratie()->getBuiten()->diff(new \DateTime());

                    if ($diff->h < $h && 0 == $diff->d && 0 == $diff->m && 0 == $diff->y) {
                        $jsonVar['message'] .= $sep.
                        __('This client has been checked out less than an hour ago. '.
                            'Are you sure you want to register him/her again?', true);

                        $jsonVar['confirm'] = true;
                        $sep = $separator;
                    }
                }
            }
        } catch (EntityNotFoundException $e) {
            // laatste registratie is gearchiveerd, dus niet recent
        }

        if ($jsonVar['allow']) {
            $newIntakeNeeded = $klant->getLaatsteIntake()->getIntakedatum()->diff(new \DateTime())->days > 365;
            if ($newIntakeNeeded) {
                $jsonVar['message'] .= $sep.'Let op: deze persoon heeft momenteel een verlopen intake (> 1 jaar geleden). Toch inchecken?';
                $sep = $separator;
                $jsonVar['confirm'] = true;
            }

            $actieveSchorsingen = $this->schorsingDao->findActiefByKlantAndLocatie($klant, $locatie);
            if (count($actieveSchorsingen) > 0) {
                $jsonVar['message'] .= $sep.'Let op: deze persoon is momenteel op deze locatie geschorst. Toch inchecken?';
                $sep = $separator;
                $jsonVar['confirm'] = true;
            }

            $tbcValid = \Configure::read('TBC_months_period') * 30 * DAY;
            $newTbcCheckNeeded = (!$klant->getLaatsteTbcControle() || $klant->getLaatsteTbcControle()->diff(new \DateTime()) > $tbcValid);
            if ($newTbcCheckNeeded && $locatie->isTbcCheck()) {
                $jsonVar['message'] .= $sep.'Let op: deze persoon heeft een nieuwe TBC-check nodig. Toch inchecken?';
                $jsonVar['confirm'] = true;
                $sep = $separator;
            }

            if (count($klant->getOpmerkingen()) > 0) {
                $laatsteOpmerking = end($klant->getOpmerkingen()->toArray());
                if (!$laatsteOpmerking->isGezien()) {
                    $jsonVar['message'] .= $sep.'Laatste opmerking ('.$laatsteOpmerking->getCreated()->format('d-m-Y').'): '.$laatsteOpmerking->getBeschrijving();
                    $jsonVar['confirm'] = true;
                    $sep = $separator;
                }
            }
        }

        return new JsonResponse($jsonVar);
    }

    /**
     * @Route("/registratieCheckOut/{registratie}")
     */
    public function registratieCheckOutAction(Request $request, Registratie $registratie)
    {
        $this->dao->checkout($registratie);

        $params = $this->activeAction($request, $registratie->getLocatie());

        return $this->render('InloopBundle:registraties:_active.html.twig', $params);
    }

    /**
     * @Route("/checkoutAll/{locatie}")
     */
    public function checkoutAllAction(Request $request, Locatie $locatie)
    {
        $filter = new RegistratieFilter($locatie);
        $registraties = $this->dao->findActive(null, $filter);

        foreach ($registraties as $registratie) {
            $this->dao->checkout($registratie);
        }

        $params = $this->activeAction($request, $registratie->getLocatie());

        return $this->render('InloopBundle:registraties:_active.html.twig', $params);
    }

    /**
     * @Route("/{registratie}/delete")
     */
    public function deleteAction(Request $request, Registratie $registratie)
    {
        $this->dao->delete($registratie);

        $params = $this->activeAction($request, $registratie->getLocatie());

        return $this->render('InloopBundle:registraties:_active.html.twig', $params);
    }

    /**
     * @Route("/ajaxAddRegistratie/{klant}/{locatie}")
     */
    public function ajaxAddRegistratieAction(Request $request, Klant $klant, Locatie $locatie)
    {
        $this->dao->checkoutKlantFromAllLocations($klant);
        $this->dao->create(new Registratie($klant, $locatie));

        $params = $this->indexAction($request, $locatie);

        return $this->render('InloopBundle:registraties:_index.html.twig', $params);
    }

    /**
     * @Route("/active/{locatie}")
     */
    public function activeAction(Request $request, Locatie $locatie)
    {
        $filter = new RegistratieFilter($locatie);
        $form = $this->createForm(RegistratieFilterType::class, $filter);
        $form->handleRequest($request);

        $page = $request->get('page', 1);
        $pagination = $this->dao->findActive($page, $filter);

        return [
            'locatie' => $locatie,
            'filter' => isset($form) ? $form->createView() : null,
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/history/{locatie}")
     */
    public function historyAction(Request $request, Locatie $locatie)
    {
        $filter = new RegistratieHistoryFilter($locatie);
        $form = $this->createForm(RegistratieHistoryFilterType::class, $filter);
        $form->handleRequest($request);

        $page = $request->get('page', 1);
        $pagination = $this->dao->findHistory($page, $filter);

        return [
            'locatie' => $locatie,
            'filter' => isset($form) ? $form->createView() : null,
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/{registratie}/douche/add")
     */
    public function doucheAddAction(Request $request, Registratie $registratie)
    {
        // set position in queue
        $queue = $this->dao->findShowerQueue($registratie->getLocatie());
        $registratie->setDouche(1 + count($queue));
        $this->dao->update($registratie);

        $params = $this->activeAction($request, $registratie->getLocatie());

        return $this->render('InloopBundle:registraties:_active.html.twig', $params);
    }

    /**
     * @Route("/{registratie}/douche/del")
     */
    public function doucheDelAction(Request $request, Registratie $registratie)
    {
        if ($registratie->getDouche() > 0) {
            $registratie->setDouche(-1);
        } else {
            $registratie->setDouche(0);
        }
        $this->dao->update($registratie);
        $this->dao->reorderShowerQueue($registratie->getLocatie());

        $params = $this->activeAction($request, $registratie->getLocatie());

        return $this->render('InloopBundle:registraties:_active.html.twig', $params);
    }

    /**
     * @Route("/{registratie}/mw/add")
     */
    public function mwAddAction(Request $request, Registratie $registratie)
    {
        // set position in queue
        $queue = $this->dao->findMwQueue($registratie->getLocatie());
        $registratie->setMw(1 + count($queue));
        $this->dao->update($registratie);

        $params = $this->activeAction($request, $registratie->getLocatie());

        return $this->render('InloopBundle:registraties:_active.html.twig', $params);
    }

    /**
     * @Route("/{registratie}/mw/del")
     */
    public function mwDelAction(Request $request, Registratie $registratie)
    {
        if ($registratie->getMw() > 0) {
            $registratie->setMw(-1);
        } else {
            $registratie->setMw(0);
        }
        $this->dao->update($registratie);
        $this->dao->reorderMwQueue($registratie->getLocatie());

        $params = $this->activeAction($request, $registratie->getLocatie());

        return $this->render('InloopBundle:registraties:_active.html.twig', $params);
    }

    /**
     * @Route("/{registratie}/kleding/{value}")
     */
    public function updateKledingAction(Request $request, Registratie $registratie, $value)
    {
        $registratie->setKleding((int) $value);
        $this->dao->update($registratie);

        $params = $this->activeAction($request, $registratie->getLocatie());

        return $this->render('InloopBundle:registraties:_active.html.twig', $params);
    }

    /**
     * @Route("/{registratie}/maaltijd/{value}")
     */
    public function updateMaaltijdAction(Request $request, Registratie $registratie, $value)
    {
        $registratie->setMaaltijd((int) $value);
        $this->dao->update($registratie);

        $params = $this->activeAction($request, $registratie->getLocatie());

        return $this->render('InloopBundle:registraties:_active.html.twig', $params);
    }

    /**
     * @Route("/{registratie}/activering/{value}")
     */
    public function updateActiveringAction(Request $request, Registratie $registratie, $value)
    {
        $registratie->setActivering((int) $value);
        $this->dao->update($registratie);

        $params = $this->activeAction($request, $registratie->getLocatie());

        return $this->render('InloopBundle:registraties:_active.html.twig', $params);
    }

    /**
     * @Route("/{registratie}/veegploeg/{value}")
     */
    public function updateVeegploegAction(Request $request, Registratie $registratie, $value)
    {
        $registratie->setVeegploeg((int) $value);
        $this->dao->update($registratie);

        $params = $this->activeAction($request, $registratie->getLocatie());

        return $this->render('InloopBundle:registraties:_active.html.twig', $params);
    }

    public function isAuthorized()
    {
        if (!parent::isAuthorized()) {
            return false;
        }

        $user_groups = $this->AuthExt->user('Group');
        if (empty($user_groups)) {
            return false;
        }

        $username = $this->AuthExt->user('username');
        $volunteers = \Configure::read('ACL.volunteers');

        $request = $this->getRequest();
        if ($this->getRequest()->attributes->has('locatie')) {
            $locatie = $this->getRequest()->attributes->get('locatie');
        } elseif ($this->getRequest()->attributes->has('registratie')) {
            $locatie = $this->getRequest()->attributes->get('registratie')->getLocatie();
        }

        if ($locatie && isset($volunteers[$username])) {
            if ($locatie->getId() != $volunteers[$username]) {
                return false;
            }
        }

        return true;
    }
}
