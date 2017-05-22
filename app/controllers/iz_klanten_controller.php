<?php

use AppBundle\Entity\Klant;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\KlantFilterType;
use IzBundle\Form\IzKlantFilterType;
use IzBundle\Form\IzKlantSelectType;
use IzBundle\Service\KlantDaoInterface;
use IzBundle\Entity\IzKlant;
use AppBundle\Form\KlantType;

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

//         $filename = sprintf('iz-deelnemers-%s.csv', (new \DateTime())->format('d-m-Y'));
//         $this->header('Content-type: text/csv');
//         $this->header(sprintf('Content-Disposition: attachment; filename="%s";', $filename));

        $filename = sprintf('iz-deelnemers-%s.xls', (new \DateTime())->format('d-m-Y'));
        $this->header('Content-type: application/vnd.ms-excel');
        $this->header(sprintf('Content-Disposition: attachment; filename="%s";', $filename));
        $this->header('Content-Transfer-Encoding: binary');

        $this->set('klanten', $klanten);
        $this->render('download', false);
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
