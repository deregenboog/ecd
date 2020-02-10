<?php

namespace GaBundle\Controller;

use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\KlantFilterType;
use AppBundle\Service\KlantDaoInterface;
use GaBundle\Entity\KlantDossier;
use GaBundle\Form\KlantdossierFilterType;
use GaBundle\Form\KlantdossierType;
use GaBundle\Service\KlantdossierDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/klantdossiers")
 * @Template
 */
class KlantdossiersController extends DossiersController
{
    protected $title = 'Deelnemers';
    protected $entityName = 'deelnemer';
    protected $entityClass = Klantdossier::class;
    protected $formClass = KlantdossierType::class;
    protected $filterFormClass = KlantdossierFilterType::class;
    protected $baseRouteName = 'ga_klantdossiers_';

    /**
     * @var KlantdossierDaoInterface
     *
     * @DI\Inject("GaBundle\Service\KlantdossierDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("ga.export.klantdossiers")
     */
    protected $export;

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("AppBundle\Service\KlantDao")
     */
    private $klantDao;

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

    protected function getDownloadFilename()
    {
        return sprintf('groepsactiviteiten-deelnemers-%s.xls', (new \DateTime())->format('d-m-Y'));
    }

    private function doSearch(Request $request)
    {
        $form = $this->getForm(KlantFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'bsn', 'geboortedatum'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $count = (int) $this->klantDao->countAll($form->getData());
            if (0 === $count) {
                $this->addFlash('info', sprintf('De zoekopdracht leverde geen resultaten op. Maak een nieuwe %s aan.', $this->entityName));

                return $this->redirectToRoute($this->baseRouteName.'add', ['klant' => 'new']);
            }

            if ($count > 100) {
                $form->addError(new FormError('De zoekopdracht leverde teveel resultaten op. Probeer het opnieuw met een specifiekere zoekopdracht.'));

                return [
                    'form' => $form->createView(),
                ];
            }

            return [
                'form' => $form->createView(),
                'klanten' => $this->klantDao->findAll(null, $form->getData()),
            ];
        }

        return [
            'form' => $form->createView(),
        ];
    }

    protected function doAdd(Request $request)
    {
        $klantId = $request->get('klant');
        if ('new' === $klantId) {
            $klant = new Klant();
        } else {
            $klant = $this->klantDao->find($klantId);
        }

        // redirect if already exists
        $dossier = $this->dao->findOneByKlant($klant);
        if ($dossier) {
            return $this->redirectToView($dossier);
        }

        $dossier = new Klantdossier($klant);
        $this->formClass = KlantdossierType::class;
        $this->forceRedirect = true;

        return $this->processForm($request, $dossier);
    }

    protected function addParams($entity, Request $request)
    {
        assert($entity instanceof Klantdossier);

        if (!$entity->getKlant()->getId()) {
            return [];
        }

        $event = new DienstenLookupEvent($entity->getKlant()->getId());
        if ($event->getKlantId()) {
            $this->get('event_dispatcher')->dispatch(Events::DIENSTEN_LOOKUP, $event);
        }

        return [
            'diensten' => $event->getDiensten(),
        ];
    }
}
