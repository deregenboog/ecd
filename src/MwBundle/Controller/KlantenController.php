<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\AmocLand;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Land;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use AppBundle\Service\KlantDaoInterface;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Afsluiting;
use MwBundle\Entity\Verslag;
use MwBundle\Form\AanmeldingType;
use MwBundle\Form\AfsluitingType;
use InloopBundle\Form\KlantType;
use JMS\DiExtraBundle\Annotation as DI;
use MwBundle\Entity\Document;
use MwBundle\Entity\Info;
use MwBundle\Form\InfoType;
use MwBundle\Form\KlantFilterType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/klanten")
 * @Template
 */
class KlantenController extends AbstractController
{
    protected $title = 'Klanten';
    protected $entityName = 'klant';
    protected $entityClass = Klant::class;
    protected $formClass = KlantType::class;
    protected $filterFormClass = KlantFilterType::class;
    protected $baseRouteName = 'mw_klanten_';
    protected $searchFilterTypeClass = AppKlantFilterType::class;

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("MwBundle\Service\KlantDao")
     */
    protected $dao;

    /**
     * @var \AppBundle\Service\KlantDaoInterface
     *
     * @DI\Inject("AppBundle\Service\KlantDao")
     */
    protected $klantDao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("mw.export.klanten")
     */
    protected $export;

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
     * @Route("/{klant}/info")
     */
    public function infoEditAction(Request $request, Klant $klant)
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository(Info::class)->findOneBy(['klant' => $klant]);
        if (!$entity) {
            $entity = new Info($klant);
        }

        $form = $this->getForm(InfoType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if (!$entity->getId()) {
                    $em->persist($entity);
                }
                $em->flush();
                $this->addFlash('success', 'Info is opgeslagen.');
            } catch(UserException $e) {
//                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->afterFormSubmitted($request, $entity);
        }

        return [
            'form' => $form->createView(),
            'klant' => $klant,
        ];
    }

    /**
     * @Template
     */
    public function _documentenAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);
        $documenten = $this->getEntityManager()->getRepository(Document::class)
            ->findBy(['klant' => $klant], ['id' => 'DESC']);

        return [
            'klant' => $klant,
            'documenten' => $documenten,
        ];
    }

    /**
     * @Template
     */
    public function _mwAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);
        $info = $this->getEntityManager()->getRepository(Info::class)->findOneBy(['klant' => $klant]);

        return [
            'klant' => $klant,
            'info' => $info,
        ];
    }

    protected function addParams($entity, Request $request)
    {
        assert($entity instanceof Klant);

        $event = new DienstenLookupEvent($entity->getId());
        if ($event->getKlantId()) {
            $this->get('event_dispatcher')->dispatch(Events::DIENSTEN_LOOKUP, $event);
        }

        return [
            'diensten' => $event->getDiensten(),
            'amoc_landen' => $this->getAmocLanden(),
        ];
    }

    protected function getAmocLanden()
    {
        return $this->getDoctrine()->getEntityManager()->getRepository(Land::class)
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

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $count = (int) $this->klantDao->countAll($filterForm->getData());
            if (0 === $count) {
                $this->addFlash('info', sprintf('De zoekopdracht leverde geen resultaten op. Maak een nieuwe %s aan.', $this->entityName));

                return $this->redirectToRoute($this->baseRouteName.'add', ['klant' => 'new']);
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
        $klantId = $request->get('klant');
        if ('new' === $klantId) {
            $klant = new Klant();
        } else {
            $klant = $this->klantDao->find($klantId);
            if ($klant) {
                // redirect if already exists
                $mwKlant = $this->dao->find($klantId);
                if ($mwKlant) {
                    return $this->redirectToRoute("mw_klanten_addmwdossierstatus",["id"=>$klantId]);
                }
            }
        }

        $mwKlant = $klant;
        $creationForm = $this->getForm(KlantType::class, $mwKlant);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($mwKlant);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                return $this->redirectToRoute("mw_klanten_addmwdossierstatus",["id"=>$mwKlant->getId()]);
            } catch(UserException $e) {
//                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'entity' => $mwKlant,
            'creationForm' => $creationForm->createView(),
            'amoc_landen' => $this->getAmocLanden(),
        ];
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);

        $afsluiting = new Afsluiting($klant, $this->getMedewerker());

        $form = $this->getForm(AfsluitingType::class, $afsluiting);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $entityManager->persist($afsluiting);
                $entityManager->flush();

                $this->addFlash('success', 'Mw dossier is afgesloten');
            } catch(UserException $e) {
//                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
            if(array_key_exists("inloopSluiten",$request->get('afsluiting')) )
            {
                if($klant->getHuidigeStatus() instanceof \InloopBundle\Entity\Aanmelding)
                {
                    return $this->redirectToRoute("inloop_klanten_close",["id"=>$id,"redirect"=>$this->generateUrl("mw_klanten_index")]);
                }

            }
            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('mw_klanten_index');
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
        $aanmelding = new Aanmelding($klant, $this->getMedewerker());

        $form = $this->getForm(AanmeldingType::class, $aanmelding);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $entityManager->persist($aanmelding);
                $entityManager->flush();

                $this->addFlash('success', 'Mw dossier is heropend');
            } catch(UserException $e) {
//                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('mw_klanten_index');
        }

        return [
            'klant' => $klant,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/add_MwDossierStatus/")
     */
    public function addMwDossierStatusAction(Request $request, $id)
    {

        $klant = $this->dao->find($id);
        $entity = new Aanmelding($klant,$this->getMedewerker());

        $form = $this->getForm(AanmeldingType::class,$entity);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $entityManager->persist($entity);
                $entityManager->flush();

                $this->addFlash('success', 'Mw dossier is aangemaakt');
            } catch(UserException $e) {
//                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToView($klant);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }


    /**
     * @Route("/{id}/edit_MwDossierStatus/{statusId}")
     */
    public function editMwDossierStatusAction(Request $request, $id,$statusId)
    {

        $klant = $this->dao->find($id);
        $mwStatus = $klant->getMwStatus($statusId);

        $type = null;
        if($mwStatus instanceof Aanmelding)
        {
            $type = AanmeldingType::class;
        }
        else if($mwStatus instanceof Afsluiting)
        {
            $type = AfsluitingType::class;
        }
        $form = $this->getForm($type, $mwStatus);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $entityManager->persist($mwStatus);
                $entityManager->flush();

                $this->addFlash('success', 'Mw dossier is gewijzigd');
            } catch(UserException $e) {
//                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToRoute('mw_klanten_index');
        }

        return [
            'entity' => $mwStatus,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/addHiPrio/")
     */
    public function addHiPrio(Request $request, $id)
    {
        $entityManager = $this->getEntityManager();
        try {
            $klant = $this->dao->find($id);

            //Wanneer klant nog niet bestat, dossier aanmaken
            if(!$klant->getHuidigeMwStatus() instanceof Aanmelding)
            {
                $entity = new Aanmelding($klant,$this->getMedewerker());
                $entity->setDatum(new \DateTime());
                $entityManager->persist($entity);
                $entityManager->flush();
                $this->addFlash('success', 'Mw dossier is aangemaakt');
            }
            $locatieRep = $this->getEntityManager()->getRepository("InloopBundle:Locatie");
            $locatie = $locatieRep->findOneBy(['naam'=>'Wachtlijst Economisch Daklozen']);

                //als nieuwste verslag niet op wachtlijst is, dan op wachtlijst zetten
                if($klant->getAantalVerslagen() < 1 || $klant->getVerslagen()->first()->getLocatie() !== $locatie) {
                    $verslag = new Verslag($klant);
                    $verslag->setDatum(new \DateTime());
                    $verslag->setOpmerking("Toegevoegd vanuit TW");
                    $verslag->setMedewerker($this->getMedewerker());
                    $verslag->setLocatie($locatie);

                    $entityManager->persist($verslag);
                    $entityManager->flush($verslag);
                    $this->addFlash('success', 'Klant is op Wachtlijst Economisch Daklozen gezet');
                }
                else
                {
                    $this->addFlash('danger', 'Klant staat al op Wachtlijst Economisch Daklozen.');
                }

            } catch(UserException $e) {
//                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            if ($url = $request->get('redirect')) {
                return $this->redirect($url);
            }

            return $this->redirectToView($klant);


        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }
}
