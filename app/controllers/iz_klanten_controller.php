<?php

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\KlantFilterType;
use IzBundle\Form\IzKlantFilterType;
use IzBundle\Form\IzKlantSelectType;
use IzBundle\Service\KlantDaoInterface;
use IzBundle\Entity\IzKlant;
use AppBundle\Form\KlantType;
use Exporter\Writer\XmlExcelWriter;
use Exporter\Handler;
use Exporter\Source\IteratorSourceIterator;
use Exporter\Source\ArraySourceIterator;
use Exporter\Writer\XlsWriter;

class IzKlantenController extends AppController
{
    /**
     * Don't use CakePHP models.
     */
    public $uses = [];

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

    private $enabledFilters = [
        'afsluitDatum',
        'openDossiers',
        'klant' => ['id', 'voornaam', 'achternaam', 'geboortedatumRange', 'stadsdeel'],
        'izProject',
        'medewerker',
        'zonderActieveHulpvraag',
        'zonderActieveKoppeling',
    ];

    /**
     * @var KlantDaoInterface
     */
    private $klantDao;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->klantDao = $this->container->get('iz.dao.klant');
    }

    public function index()
    {
        $form = $this->createFilter();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('download')->isClicked()) {
                return $this->download($form->getData());
            }
        }

        $page = $this->getRequest()->get('page', 1);
        $pagination = $this->klantDao->findAll($page, $form->getData());

        $this->set('form', $form->createView());
        $this->set('pagination', $pagination);
    }

    public function download(FilterInterface $filter)
    {
        $klanten = $this->klantDao->findAll(null, $filter);

        $format = 'Y-m-d';
        $data = [];
        foreach ($klanten as $izKlant) {
            /* @var IzKlant $izKlant */

            $projecten = [];
            foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
                $projecten[] = $izHulpvraag->getIzProject();
            }

            $medewerkers = [];
            foreach ($izKlant->getIzHulpvragen() as $izHulpvraag) {
                $medewerkers[] = $izHulpvraag->getMedewerker();
            }

            $data[] = [
                'Nummer' => $izKlant->getKlant()->getId(),
                'Intakedatum' => $izKlant->getIzIntake() ? $izKlant->getIzIntake()->getIntakeDatum()->format($format) : null,
                'Afsluitdatum' => $izKlant->getAfsluitDatum() ? $izKlant->getAfsluitDatum()->format($format) : null,
                'Geslacht' => $izKlant->getKlant()->getGeslacht(),
                'Voornaam' => $izKlant->getKlant()->getVoornaam(),
                'Tussenvoegsel' => $izKlant->getKlant()->getTussenvoegsel(),
                'Achternaam' => $izKlant->getKlant()->getAchternaam(),
                'Geboortedatum' => $izKlant->getKlant()->getGeboortedatum() ? $izKlant->getKlant()->getGeboortedatum()->format($format) : null,
                'E-mail' => $izKlant->getKlant()->getEmail(),
                'Adres' => $izKlant->getKlant()->getAdres(),
                'Postcode' => $izKlant->getKlant()->getPostcode(),
                'Woonplaats' => $izKlant->getKlant()->getPlaats(),
                'Mobiel' => $izKlant->getKlant()->getMobiel(),
                'Telefoon' => $izKlant->getKlant()->getTelefoon(),
                'Werkgebied' => $izKlant->getKlant()->getWerkgebied(),
                'Gezin met kinderen' => $izKlant->getIzIntake() ? $izKlant->getIzIntake()->isGezinMetKinderen() : null,
                'Geen post' => $izKlant->getKlant()->getGeenPost(),
                'Geen e-mail' => $izKlant->getKlant()->getGeenEmail(),
                'Project(en)' => implode(', ', $projecten),
                'Medewerker(s)' => implode(', ', $medewerkers),
                'Gekoppeld' => $izKlant->isGekoppeld() ? 'ja' : 'nee',
            ];
        }

        $filename = sprintf('iz-deelnemers-%s.xlsx', (new \DateTime())->format('d-m-Y'));
        $tmpFilename = sprintf('iz-deelnemers-%s.xlsx', uniqid());
        $cacheDir = $this->container->getParameter('kernel.cache_dir');

        $source = new ArraySourceIterator($data);
        $writer = new XmlExcelWriter($cacheDir.'/'.$tmpFilename, true, [
            'Nummer' => 'Number',
            'Intakedatum' => 'Date',
            'Afsluitdatum' => 'Date',
            'Geslacht' => 'String',
            'Voornaam' => 'String',
            'Tussenvoegsel' => 'String',
            'Achternaam' => 'String',
            'Geboortedatum' => 'Date',
            'E-mail' => 'String',
            'Adres' => 'String',
            'Postcode' => 'String',
            'Woonplaats' => 'String',
            'Mobiel' => 'String',
            'Telefoon' => 'String',
            'Werkgebied' => 'String',
            'Gezin met kinderen' => 'Number',
            'Geen post' => 'Number',
            'Geen e-mail' => 'Number',
            'Project(en)' => 'String',
        ]);
        $handler = Handler::create($source, $writer);
        $handler->export();

        $this->autoRender = false;
        $this->header('Content-type: application/vnd.ms-excel');
        $this->header(sprintf('Content-Disposition: attachment; filename="%s";', $filename));
        $this->header('Content-Transfer-Encoding: binary');

        $contents = file_get_contents($cacheDir.'/'.$tmpFilename);
        unlink($cacheDir.'/'.$tmpFilename);

        return $contents;
    }

    public function add($klantId = null)
    {
        if ($klantId) {
            if ($klantId === 'new') {
                $creationForm = $this->createForm(KlantType::class);
                $creationForm->handleRequest($this->getRequest());
                if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                    $klant = $creationForm->getData();
                    $this->getEntityManager()->persist($klant);
                    $this->getEntityManager()->flush();

                    return $this->redirect([
                        'controller' => 'iz_deelnemers',
                        'action' => 'toon_aanmelding',
                        'Klant',
                        $klant->getId(),
                    ]);
                }
                $this->set('creationForm', $creationForm->createView());

                return;
            } else {
                return $this->redirect([
                    'controller' => 'iz_deelnemers',
                    'action' => 'toon_aanmelding',
                    'Klant',
                    $klantId,
                ]);
            }
        }

        $filterForm = $this->createForm(KlantFilterType::class, null, [
            'enabled_filters' => ['naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($this->getRequest());

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $builder = $this->getEntityManager()->getRepository(Klant::class)
                ->createQueryBuilder('klant')
                ->where('klant.disabled = false')
                ->orderBy('klant.achternaam')
            ;
            $filterForm->getData()->applyTo($builder);
            $this->set('klanten', $builder->getQuery()->getResult());

            return;
        }

        $this->set('filterForm', $filterForm->createView());
    }

    private function createFilter()
    {
        $form = $this->createForm(IzKlantFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $form->handleRequest($this->getRequest());

        return $form;
    }
}
