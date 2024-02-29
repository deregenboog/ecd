<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Export\ExportInterface;
use DagbestedingBundle\Service\LocatieDaoInterface;
use Doctrine\ORM\EntityNotFoundException;
use OekraineBundle\Entity\Locatie;
use OekraineBundle\Entity\Registratie;
use OekraineBundle\Entity\Bezoeker;
use OekraineBundle\Event\Events;
use OekraineBundle\Filter\BezoekerFilter;
use OekraineBundle\Filter\RegistratieFilter;
use OekraineBundle\Filter\RegistratieHistoryFilter;
use OekraineBundle\Form\BezoekerFilterType;
use OekraineBundle\Form\RegistratieFilterType;
use OekraineBundle\Form\RegistratieHistoryFilterType;
use OekraineBundle\Form\RegistratieType;
use OekraineBundle\Security\Permissions;
use OekraineBundle\Service\BezoekerDaoInterface;
use OekraineBundle\Service\BezoekerDao;
use OekraineBundle\Service\LocatieDao;
use OekraineBundle\Service\RegistratieDao;
use OekraineBundle\Service\RegistratieDaoInterface;
use OekraineBundle\Service\SchorsingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/registraties")
 * @Template
 */
class RegistratiesController extends AbstractController
{
    protected $title = 'Bezoekersregistratie';
    protected $entityName = 'registratie';
    protected $entityClass = Registratie::class;
    protected $formClass = RegistratieType::class;
    protected $baseRouteName = 'oekraine_registraties_';

    /**
     * @var RegistratieDao
     */
    protected $dao;

    /**
     * @var BezoekerDao
     */
    protected $bezoekerDao;

    /**
     * @var LocatieDao
     */
    protected $locatieDao;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @param RegistratieDao $dao
     * @param BezoekerDao $bezoekerDao
     * @param LocatieDao $locatieDao
     * @param ExportInterface $export
     */
    public function __construct(RegistratieDao $dao, BezoekerDao $bezoekerDao, LocatieDao $locatieDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->bezoekerDao = $bezoekerDao;
        $this->locatieDao = $locatieDao;
        $this->export = $export;
    }

    /**
     * @Route("/")
     */
    public function locationSelectAction(Request $request)
    {
        $locaties = $this->getEntityManager()->getRepository(Locatie::class)
            ->createQueryBuilder('locatie')
            ->andWhere('locatie.datumVan <= DATE(CURRENT_TIMESTAMP())')
            ->andWhere('locatie.datumTot > DATE(CURRENT_TIMESTAMP()) OR locatie.datumTot IS NULL')
            ->orderBy('locatie.naam')
            ->getQuery()
            ->getResult()
        ;

        return [
            'locaties' => $locaties,
        ];
    }

    /**
     * @Route("/{locatie}", requirements={"locatie" = "\d+"})
     * @ParamConverter("locatie", class="OekraineBundle\Entity\Locatie")
     */
    public function indexAction(Request $request)
    {
        $locatie = $request->get('locatie');

        $this->denyAccessUnlessGranted(Permissions::REGISTER, $locatie);

        $filter = new BezoekerFilter();
        $filter->locatie = $locatie;

        $form = $this->getForm(BezoekerFilterType::class, $filter, [
            'attr' => ['class' => 'ajaxFilter'],
            'enabled_filters' => [
                'appKlant' => ['id', 'voornaam', 'achternaam', 'geboortedatum', 'geslacht'],
                'woonlocatie',
                'filter',
            ],
        ]);
        $form->handleRequest($request);

        if (!$request->isXmlHttpRequest()) {
            return [
                'locatie' => $locatie,
                'filter' => $form->createView(),
            ];
        }

        $this->getEntityManager()->getFilters()->enable('overleden');

        $page = $request->get('page', 1);
        $pagination = $this->bezoekerDao->findAll($page, $filter);

        return $this->render('oekraine/registraties/_index.html.twig', [
            'locatie' => $locatie,
            'filter' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/active/{locatie}")
     */
    public function activeAction(Request $request, Locatie $locatie)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $locatie);

        $filter = new RegistratieFilter($locatie);
        $form = $this->getForm(RegistratieFilterType::class, $filter, [
            'attr' => ['class' => 'ajaxFilter'],
        ]);
        $form->handleRequest($request);

        if (!$request->isXmlHttpRequest()) {
            return [
                'locatie' => $locatie,
                'filter' => $form->createView(),
            ];
        }

        $page = $request->get('page', 1);
        $pagination = $this->dao->findActive($page, $filter);

        $klantIds = array_map(function (Registratie $registratie) {
            return $registratie->getBezoeker()->getAppKlant()->getId();
        }, $pagination->getItems());
        $event = new GenericEvent($klantIds, ['geen_activering_klant_ids' => []]);
        $this->eventDispatcher->dispatch($event, Events::GEEN_ACTIVERING);

        return $this->render('oekraine/registraties/_active.html.twig', [
            'locatie' => $locatie,
            'filter' => isset($form) ? $form->createView() : null,
            'pagination' => $pagination,
            'geen_activering_klant_ids' => $event->getArgument('geen_activering_klant_ids'),
        ]);
    }

    /**
     * @Route("/history/{locatie}")
     */
    public function historyAction(Request $request, Locatie $locatie)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $locatie);

        $filter = new RegistratieHistoryFilter($locatie);
        $form = $this->getForm(RegistratieHistoryFilterType::class, $filter, [
            'attr' => ['class' => 'ajaxFilter'],
        ]);
        $form->handleRequest($request);

        if (!$request->isXmlHttpRequest()) {
            return [
                'locatie' => $locatie,
                'filter' => $form->createView(),
            ];
        }

        $page = $request->get('page', 1);
        $pagination = $this->dao->findHistory($page, $filter);

        $klantIds = array_map(function (Registratie $registratie) {
            return $registratie->getBezoeker()->getId();
        }, $pagination->getItems());
        $event = new GenericEvent($klantIds, ['geen_activering_klant_ids' => []]);
        $this->eventDispatcher->dispatch($event, Events::GEEN_ACTIVERING);

        return $this->render('oekraine/registraties/_history.html.twig', [
            'locatie' => $locatie,
            'filter' => isset($form) ? $form->createView() : null,
            'pagination' => $pagination,
            'geen_activering_klant_ids' => $event->getArgument('geen_activering_klant_ids'),
        ]);
    }

    /**
     * This logic is copied from original code base. Candidate for refactoring.
     * ...
     * @todo
     *
     * @Route("/jsonCanRegister/{bezoeker}/{locatie}")
     */
    public function jsonCanRegisterAction(Bezoeker $bezoeker, Locatie $locatie, $h = 1)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $locatie);

        $jsonVar = [
            'confirm' => false,
            'allow' => true,
            'message' => '',
        ];

        $sep = '';
        $separator = PHP_EOL.PHP_EOL;


        try {
            if ($bezoeker->getLaatsteRegistratie()) {
                if (!$bezoeker->getLaatsteRegistratie()->getBuiten()) {
                    if ($bezoeker->getLaatsteRegistratie()->getLocatie() == $locatie) {
                        $jsonVar['allow'] = false;
                        $jsonVar['message'] .= $sep.'Deze klant is op dit moment al ingecheckt op deze locatie.';
                    } else {
                        $jsonVar['confirm'] = true;
                        $jsonVar['message'] .= $sep.'Deze klant is op dit moment al ingecheckt op een andere locatie. Toch inchecken?';
                        $sep = $separator;
                    }

                    $sep = $separator;
                } else {
                    $diff = $bezoeker->getLaatsteRegistratie()->getBuiten()->diff(new \DateTime());

                    if ($diff->h < $h && 0 == $diff->d && 0 == $diff->m && 0 == $diff->y) {
                        $jsonVar['confirm'] = true;
                        $jsonVar['message'] .= $sep.'Deze klant is minder dan een uur geleden uitgechecked. Opnieuw registreren?';
                        $sep = $separator;
                    }
                }
            }
        } catch (EntityNotFoundException $e) {
            // laatste registratie is gearchiveerd, dus niet recent
        }

        if ($jsonVar['allow']) {
            if(($laatsteIntake = $bezoeker->getLaatsteIntake()) == null)
            {
                $jsonVar['message'] .= $sep.'Let op: deze persoon heeft geen intake. Toch inchecken?';
                $sep = $separator;
                $jsonVar['confirm'] = true;
            }
//            $newIntakeNeeded = ;
            if ($laatsteIntake !== null && $laatsteIntake->getIntakedatum()->diff(new \DateTime())->days > 365) {
                $jsonVar['message'] .= $sep.'Let op: deze persoon heeft momenteel een verlopen intake (> 1 jaar geleden). Toch inchecken?';
                $sep = $separator;
                $jsonVar['confirm'] = true;
            }
            if (( ($laatsteRegistratie = $bezoeker->getLaatsteRegistratie()) !== null) && $laatsteRegistratie->getBuiten() !== null  && $laatsteRegistratie->getBuiten()->diff(new \DateTime() )->days > 730  ) {
                $jsonVar['message'] .= $sep.'Let op: deze persoon heeft zich al twee jaar nergens meer geregistreerd en heeft een nieuwe intake nodig. Toch inchecken?';
                $sep = $separator;
                $jsonVar['confirm'] = true;
            }

//            $actieveSchorsingen = $this->schorsingDao->findActiefByKlantAndLocatie($bezoeker, $locatie);
//            if (count($actieveSchorsingen) > 0) {
//                $jsonVar['message'] .= $sep.'Let op: deze persoon is momenteel op deze locatie geschorst. Toch inchecken?';
//                $sep = $separator;
//                $jsonVar['confirm'] = true;
//            }
//
//            $terugkeergesprekNodig = $this->schorsingDao->findTerugkeergesprekNodigByKlantAndLocatie($bezoeker, $locatie);
//            if (count($terugkeergesprekNodig) > 0) {
//                $jsonVar['message'] .= $sep.'Let op: deze persoon is 14 dagen of langer geschorst geweest en heeft een terugkeergesprek nodig.';
//                $sep = $separator;
//                $jsonVar['confirm'] = true;
//            }


//            if (count($bezoeker->getOpenstaandeOpmerkingen()) > 0) {
//                $opmerkingen = $bezoeker->getOpenstaandeOpmerkingen()->toArray();
//                foreach ($opmerkingen as $opmerking) {
//                    $jsonVar['message'] .= $sep.'Openstaande opmerking ('.$opmerking->getCreated()->format('d-m-Y').'): '.$opmerking->getBeschrijving();
//                    $sep = $separator;
//                }
//                $jsonVar['confirm'] = true;
//            }
        }

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
     * @ParamConverter("registratie", class="OekraineBundle\Entity\Registratie")
     */
    public function deleteAction(Request $request, $registratie)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $registratie->getLocatie());

        $this->dao->delete($registratie);

        return new JsonResponse();
    }

    /**
     * @Route("/ajaxAddRegistratie/{bezoeker}/{locatie}")
     */
    public function ajaxAddRegistratieAction(Request $request, Bezoeker $bezoeker, Locatie $locatie)
    {
        $this->denyAccessUnlessGranted(Permissions::REGISTER, $locatie);

        /**
         * Wanneer een klant ergens incheckt, wordt hij eerst overal uitgecheckt.
         */
        $this->dao->checkoutBezoekerFromAllLocations($bezoeker);
        $registratie = $this->dao->create(new Registratie($bezoeker, $locatie));
        $bezoeker->addRegistratie($registratie);
        $this->bezoekerDao->update($bezoeker);
        return new JsonResponse();
    }
}
