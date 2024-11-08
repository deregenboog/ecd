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
use AppBundle\Form\BaseType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use Doctrine\ORM\Mapping as ORM;
use InloopBundle\Entity\Locatie;
use InloopBundle\Form\KlantType;
use InloopBundle\Service\LocatieDaoInterface;
use Knp\Component\Pager\Paginator;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Afsluiting;
use MwBundle\Entity\Document;
use MwBundle\Entity\Info;
use MwBundle\Entity\Project;
use MwBundle\Entity\Verslag;
use MwBundle\Form\AanmeldingType;
use MwBundle\Form\AfsluitingType;
use MwBundle\Form\InfoType;
use MwBundle\Form\KlantFilterType;
use MwBundle\Service\KlantDaoInterface;
use MwBundle\Service\MwDossierStatusDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/klanten")
 *
 * @Template
 *
 * @ORM\Embeddable
 *
 * @ORM\Entity
 *
 * @ORM\Table(name="klanten_controller")
 */
class KlantenController extends AbstractController
{
    protected $entityName = 'klant';
    protected $entityClass = Klant::class;
    protected $formClass = KlantType::class;
    protected $filterFormClass = KlantFilterType::class;
    protected $baseRouteName = 'mw_klanten_';
    protected $searchFilterTypeClass = AppKlantFilterType::class;

    /**
     * @var KlantDaoInterface
     */
    protected $dao;

    /**
     * @var \AppBundle\Service\KlantDaoInterface
     */
    protected $klantDao;

    /**
     * @var LocatieDaoInterface
     */
    protected $locatieDao;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct(KlantDaoInterface $dao, \AppBundle\Service\KlantDaoInterface $klantDao, LocatieDaoInterface $locatieDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->klantDao = $klantDao;
        $this->locatieDao = $locatieDao;
        $this->export = $export;
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        $response = parent::viewAction($request, $id);
        if (is_array($response)) {
            $response['allRows'] = $this->locatieDao->findAllActiveLocationsOfTypeInloop();
        }

        /**
         * The regular view is altered to pass an adjusted set of verslagen to the view.
         *
         * There is a need to merge the verslagen of klant and TW Deelnemer.
         * Therefore we utilise this method to get the TW deelnemer and merge the verslagen.
         */
        $klant = $response['entity'];

        $mwVerslagen = $klant->getVerslagen();
        $response['verslagen'] = $mwVerslagen;

        $event = new DienstenLookupEvent($klant->getId());
        if ($event->getKlantId()) {
            $this->eventDispatcher->dispatch($event, Events::DIENSTEN_LOOKUP);
        }

        $diensten = $event->getDiensten();
        $twKlant = null;
        foreach ($diensten as $dienst) {
            if ('Tijdelijk wonen' == $dienst->getNaam()) {
                $twKlant = $dienst->getEntity();
            }
        }

        if (null !== $twKlant && $twKlant instanceof \TwBundle\Entity\Klant) {
            $twVerslagen = $this->getEntityManager()->getRepository(\TwBundle\Entity\Verslag::class)->getMwVerslagenForKlant($twKlant);

            $combinedVerslagen =
                array_merge($twVerslagen, $mwVerslagen->toArray())
            ;
            usort($combinedVerslagen, function ($a, $b) {
                // Assuming getCreatedAt() or similar method returns the DateTime object
                return $b->getDatum() <=> $a->getDatum();
            });

            $response['verslagen'] = $combinedVerslagen;
        }

        // ga bij TW de deelnemer en de verslagen ophalen, combineer die. zet die in de repsonse array en pas de view aan.
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
     * @Route("/{klant}/info")
     */
    public function infoEditAction(Request $request, Klant $klant)
    {
        $em = $this->getEntityManager();
        // $entity = $em->getRepository(Info::class)->findOneBy(['klant' => $klant]);
        $entity = $klant->getInfo();
        if (!$entity) {
            $entity = new Info($klant);
        }

        $form = $this->getForm(InfoType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $klant->setInfo($entity);
                $em->persist($klant);
                $em->flush();
                $this->addFlash('success', 'Info is opgeslagen.');
            } catch (UserException $e) {
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->afterFormSubmitted($request, $entity, null);
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

    protected function addParams($entity, Request $request): array
    {
        assert($entity instanceof Klant);

        $event = new DienstenLookupEvent($entity->getId());
        if ($event->getKlantId()) {
            $this->eventDispatcher->dispatch($event, Events::DIENSTEN_LOOKUP);
        }

        return [
            'diensten' => $event->getDiensten(),
            'amoc_landen' => $this->getAmocLanden(),
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
                if ($mwKlant && !$mwKlant->getHuidigeMwStatus() instanceof Aanmelding) {
                    return $this->redirectToRoute('mw_klanten_addmwdossierstatus', ['id' => $klantId]);
                }
                $this->addFlash('warning', 'Deze klant is al aangemeld. Wanneer u de klant opnieuw wilt aanmelden dient het dossier eerst gesloten te worden.');

                return $this->redirectToRoute('mw_klanten_view', ['id' => $klantId]);
            }
        }

        $mwKlant = $klant;
        $creationForm = $this->getForm(KlantType::class, $mwKlant);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($mwKlant);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                return $this->redirectToRoute('mw_klanten_addmwdossierstatus', ['id' => $mwKlant->getId()]);
            } catch (UserException $e) {
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
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

        $hds = $klant->getHuidigeMwStatus();
        if (!$hds instanceof Aanmelding) {
            throw new UserException('Kan geen dossier sluiten dat nu niet aangemeld is.');
        }
        $afsluiting = new Afsluiting($this->getMedewerker());
        $afsluiting->setKlant($klant);

        /*
         * Copy project data from aanmelding to afsluiting so the project gets maintained in de afsluiting.
         */
        if (null !== $klant->getAanmelding() && null !== $project = $klant->getAanmelding()->getProject()) {
            $afsluiting->setProject($project);
        }

        $form = $this->getForm(AfsluitingType::class, $afsluiting);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $klant->setHuidigeMwStatus($afsluiting);
                $entityManager->persist($klant);
                $entityManager->flush();

                $this->addFlash('success', 'Mw dossier is afgesloten');
            } catch (UserException $e) {
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
            if (array_key_exists('inloopSluiten', $request->get('afsluiting'))) {
                if ($klant->getHuidigeStatus() instanceof \InloopBundle\Entity\Aanmelding) {
                    return $this->redirectToRoute('inloop_klanten_close', ['id' => $id, 'redirect' => $this->generateUrl('mw_klanten_index')]);
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
        $aanmelding = new Aanmelding($this->getMedewerker());
        $aanmelding->setKlant($klant);

        $form = $this->getForm(AanmeldingType::class, $aanmelding);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $klant->setHuidigeMwStatus($aanmelding);
                $entityManager->persist($klant);
                $entityManager->flush();

                $this->addFlash('success', 'Mw dossier is heropend');
            } catch (UserException $e) {
                //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
                //                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
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
     * @Route("/{id}/dossierstatus/add")
     */
    public function addMwDossierStatusAction(Request $request, $id)
    {
        /** @var Klant $klant */
        $klant = $this->dao->find($id);

        $entity = new Aanmelding($this->getMedewerker());
        $entity->setKlant($klant);
        $info = $klant->getInfo();
        if (!$info) {
            $info = new Info($klant);
        }

        $form = $this->getForm(AanmeldingType::class, $entity);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $klant->setHuidigeMwStatus($entity);
                $klant->setInfo($info);
                $entityManager = $this->getEntityManager();
                $entityManager->persist($klant);
                $entityManager->flush();

                $this->addFlash('success', 'Mw dossier is aangemaakt');
            } catch (UserException $e) {
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
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
     * @Route("/{id}/dossierstatus/{statusId}/edit")]
     */
    public function editMwDossierStatusAction(Request $request, $id, $statusId)
    {
        $klant = $this->dao->find($id);
        $mwStatus = $klant->getMwStatus($statusId);

        $type = null;
        // prevents certain fields from being edited (like project)
        $formEditMode = BaseType::MODE_EDIT;

        if ($mwStatus instanceof Aanmelding) {
            $type = AanmeldingType::class;
            if (null == $mwStatus->getProject()) {// possibly an old aanmelding without project. Possible to set even while editting.
                $formEditMode = BaseType::MODE_ADD;
            }
        } elseif ($mwStatus instanceof Afsluiting) {
            $type = AfsluitingType::class;
        }

        $form = $this->getForm($type, $mwStatus, ['mode' => $formEditMode]);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($mwStatus->getId()) {
                    $this->beforeUpdate($klant);
                    $this->dao->update($klant);
                } else {
                    $this->beforeCreate($klant);
                    $this->dao->create($klant);
                }

                $this->addFlash('success', 'Mw dossier is gewijzigd');
            } catch (UserException $e) {
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
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
     * @Route("/{id}/dossierstatus/{statusId}/delete")]
     *
     * @Template("delete.html.twig")
     */
    public function deleteMwDossierStatusAction(Request $request, $id, $statusId)
    {
        $this->dao = new MwDossierStatusDao($this->entityManager, new Paginator($this->eventDispatcher));
        $return = parent::deleteAction($request, $statusId);

        if (is_array($return)) {
            $return['entity_name'] = 'Dossierstatus';
        }

        return $return;
    }

    /**
     * @Route("/{id}/addHiPrio/")
     */
    public function addHiPrio(Request $request, $id)
    {
        $klant = null;
        $entityManager = $this->getEntityManager();
        try {
            $klant = $this->dao->find($id);
            // Wanneer klant nog niet bestat, dossier aanmaken
            if (!$klant->getHuidigeMwStatus() instanceof Aanmelding) {
                return $this->redirectToRoute('mw_klanten_addmwdossierstatus', [
                    'id' => $klant->getId(),
                    'redirect' => $this->generateUrl('mw_klanten_addhiprio', [
                        'id' => $klant->getId(),
                    ]),
                ]);
            }

            $locatieRep = $this->getEntityManager()->getRepository(Locatie::class);
            $locatie = $locatieRep->findOneBy(['naam' => 'Wachtlijst STED']);

            $v = $klant->getAantalVerslagen();

            // als nieuwste verslag niet op wachtlijst is, dan op wachtlijst zetten
            if ($klant->getAantalVerslagen() < 1 || $klant->getVerslagen()->first()->getLocatie() !== $locatie) {
                $verslag = new Verslag($klant);
                $verslag->setDatum(new \DateTime());
                $verslag->setOpmerking('Toegevoegd vanuit TW');
                $verslag->setMedewerker($this->getMedewerker());
                $verslag->setLocatie($locatie);
                $verslag->setAccess(Verslag::ACCESS_MW);

                $entityManager->persist($verslag);
                $entityManager->flush();
                $this->addFlash('success', 'Klant is op Wachtlijst Economisch Daklozen gezet');
            } else {
                $this->addFlash('danger', 'Klant staat al op Wachtlijst Economisch Daklozen.');
            }
        } catch (UserException $e) {
            $message = $e->getMessage();
            $this->addFlash('danger', $message);
        } catch (\Exception $e) {
            $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
            $this->addFlash('danger', $message);
        }

        if ($url = $request->get('redirect')) {
            return $this->redirect($url);
        }

        return $this->redirectToView($klant);
    }
}
