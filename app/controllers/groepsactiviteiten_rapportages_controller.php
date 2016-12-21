<?php

use IzBundle\Form\IzRapportageType;
use IzBundle\Entity\IzKoppeling;
use IzBundle\Entity\IzHulpvraag;
use AppBundle\Report\Table;
use IzBundle\Entity\IzVrijwilliger;
use IzBundle\Entity\IzKlant;
use GaBundle\Form\GaRapportageType;
use GaBundle\Entity\GaGroepErOpUit;
use AppBundle\Report\Grid;
use GaBundle\Entity\GaGroepOpenHuis;
use GaBundle\Entity\GaVrijwilligerIntake;
use GaBundle\Entity\GaGroepOrganisatie;
use GaBundle\Entity\GaGroepBuurtmaatjes;

class GroepsactiviteitenRapportagesController extends AppController
{
    /**
     * Don't use CakePHP models.
     */
    public $uses = [];

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

    public function index()
    {
        $form = $this->createForm(GaRapportageType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $reportName = $form->get('rapport')->getData();
            $method = 'report_'.$reportName;
            if (method_exists($this, $method)) {
                $this->{$method}($form->get('startdatum')->getData(), $form->get('einddatum')->getData());
            }
        }

        $this->set('form', $form->createView());
    }

    private function report_buurtmaatjes_deelnemers_totaal(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $grid = $this->getDeelnemersTotaalGrid(GaGroepBuurtmaatjes::class, $startDate, $endDate, $format);

        $title = 'Buurtmaatjes deelnemers totaal';
        $reports = [[
            'title' => $title,
            'data' => $grid->render(),
        ]];

        $this->set('title', $title);
        $this->set('startDate', $startDate);
        $this->set('endDate', $endDate);
        $this->set('reports', $reports);
    }

    private function report_eropuit_deelnemers_totaal(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $grid = $this->getDeelnemersTotaalGrid(GaGroepErOpUit::class, $startDate, $endDate, $format);

        $title = 'ErOpUit deelnemers totaal';
        $reports = [[
            'title' => $title,
            'data' => $grid->render(),
        ]];

        $this->set('title', $title);
        $this->set('startDate', $startDate);
        $this->set('endDate', $endDate);
        $this->set('reports', $reports);
    }

    private function report_openhuis_deelnemers_totaal(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $grid = $this->getDeelnemersTotaalGrid(GaGroepOpenHuis::class, $startDate, $endDate, $format);

        $title = 'Open Huis deelnemers totaal';
        $reports = [[
            'title' => $title,
            'data' => $grid->render(),
        ]];

        $this->set('title', $title);
        $this->set('startDate', $startDate);
        $this->set('endDate', $endDate);
        $this->set('reports', $reports);
    }

    private function report_buurtmaatjes_deelnemers_per_stadsdeel(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $grid = $this->getDeelnemersPerStadsdeelGrid(GaGroepBuurtmaatjes::class, $startDate, $endDate, $format);

        $title = 'Buurtmaatjes deelnemers per stadsdeel';
        $reports = [[
            'title' => $title,
            'xDescription' => 'LET OP: Stadsdeel is op basis van woonadres cliënt (dus niet op basis van activiteitlocatie)',
            'yDescription' => 'Stadsdeel',
            'data' => $grid->render(),
        ]];

        $this->set('title', $title);
        $this->set('startDate', $startDate);
        $this->set('endDate', $endDate);
        $this->set('reports', $reports);
    }

    private function report_eropuit_deelnemers_per_stadsdeel(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $grid = $this->getDeelnemersPerStadsdeelGrid(GaGroepErOpUit::class, $startDate, $endDate, $format);

        $title = 'ErOpUit deelnemers per stadsdeel';
        $reports = [[
            'title' => $title,
            'xDescription' => 'LET OP: Stadsdeel is op basis van woonadres cliënt (dus niet op basis van activiteitlocatie)',
            'yDescription' => 'Stadsdeel',
            'data' => $grid->render(),
        ]];

        $this->set('title', $title);
        $this->set('startDate', $startDate);
        $this->set('endDate', $endDate);
        $this->set('reports', $reports);
    }

    private function report_openhuis_deelnemers_per_stadsdeel(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $grid = $this->getDeelnemersPerStadsdeelGrid(GaGroepOpenHuis::class, $startDate, $endDate, $format);

        $title = 'Open Huis deelnemers per stadsdeel';
        $reports = [[
            'title' => $title,
            'xDescription' => 'LET OP: Stadsdeel is op basis van woonadres cliënt (dus niet op basis van activiteitlocatie)',
            'yDescription' => 'Stadsdeel',
            'data' => $grid->render(),
        ]];

        $this->set('title', $title);
        $this->set('startDate', $startDate);
        $this->set('endDate', $endDate);
        $this->set('reports', $reports);
    }

    private function report_buurtmaatjes_vrijwilligers_totaal(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $grid = $this->getVrijwilligersTotaalGrid(GaGroepBuurtmaatjes::class, $startDate, $endDate, $format);

        $title = 'Buurtmaatjes vrijwilligers totaal';
        $reports = [[
            'title' => $title,
            'data' => $grid->render(),
        ]];

        $this->set('title', $title);
        $this->set('startDate', $startDate);
        $this->set('endDate', $endDate);
        $this->set('reports', $reports);
    }

    private function report_eropuit_vrijwilligers_totaal(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $grid = $this->getVrijwilligersTotaalGrid(GaGroepErOpUit::class, $startDate, $endDate, $format);

        $title = 'ErOpUit vrijwilligers totaal';
        $reports = [[
            'title' => $title,
            'data' => $grid->render(),
        ]];

        $this->set('title', $title);
        $this->set('startDate', $startDate);
        $this->set('endDate', $endDate);
        $this->set('reports', $reports);
    }

    private function report_openhuis_vrijwilligers_totaal(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $grid = $this->getVrijwilligersTotaalGrid(GaGroepOpenHuis::class, $startDate, $endDate, $format);

        $title = 'Open Huis vrijwilligers totaal';
        $reports = [[
            'title' => $title,
            'data' => $grid->render(),
        ]];

        $this->set('title', $title);
        $this->set('startDate', $startDate);
        $this->set('endDate', $endDate);
        $this->set('reports', $reports);
    }

    private function report_organisatie_vrijwilligers_totaal(
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $grid = $this->getVrijwilligersTotaalGrid(GaGroepOrganisatie::class, $startDate, $endDate, $format);

        $title = 'Organisatieondersteunende vrijwilligers totaal';
        $reports = [[
            'title' => $title,
            'data' => $grid->render(),
        ]];

        $this->set('title', $title);
        $this->set('startDate', $startDate);
        $this->set('endDate', $endDate);
        $this->set('reports', $reports);
    }

    private function getDeelnemersTotaalGrid(
        $class,
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $builder = $this->getEntityManager()->getRepository($class)
            ->createQueryBuilder('gaGroep')
            ->select('klant.werkgebied AS stadsdeel')
            ->addSelect('COUNT(DISTINCT gaActiviteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT gaKlantDeelname) AS aantal_deelnemers')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_unieke_deelnemers')
            ->innerJoin('gaGroep.gaActiviteiten', 'gaActiviteit')
            ->innerJoin('gaActiviteit.gaKlantDeelnames', 'gaKlantDeelname')
            ->innerJoin('gaKlantDeelname.klant', 'klant')
            ->where('gaActiviteit.datum BETWEEN :start AND :eind')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
        ;
        $data = $builder->getQuery()->getResult();

        $columns = [
            'Aantal activiteiten' => 'aantal_activiteiten',
            'Aantal deelnemers' => 'aantal_deelnemers',
            'Aantal unieke deelnemers' => 'aantal_unieke_deelnemers',
        ];
        $grid = new Grid($data, $columns);
        $grid
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setYNullReplacement('Totaal')
        ;

        return $grid;
    }

    private function getDeelnemersPerStadsdeelGrid(
        $class,
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $builder = $this->getEntityManager()->getRepository($class)
            ->createQueryBuilder('gaGroep')
//             ->select('gaGroep.werkgebied AS stadsdeel')
            ->select('klant.werkgebied AS stadsdeel')
            ->addSelect('COUNT(DISTINCT gaActiviteit) AS aantal_activiteiten')
            ->addSelect('COUNT(DISTINCT gaKlantDeelname) AS aantal_deelnemers')
            ->addSelect('COUNT(DISTINCT klant) AS aantal_unieke_deelnemers')
            ->innerJoin('gaGroep.gaActiviteiten', 'gaActiviteit')
            ->innerJoin('gaActiviteit.gaKlantDeelnames', 'gaKlantDeelname')
            ->innerJoin('gaKlantDeelname.klant', 'klant')
            ->where('gaActiviteit.datum BETWEEN :start AND :eind')
//             ->groupBy('gaGroep.werkgebied')
//             ->orderBy('gaGroep.werkgebied')
            ->groupBy('klant.werkgebied')
            ->orderBy('klant.werkgebied')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
        ;
        $data = $builder->getQuery()->getResult();

        $columns = [
            'Aantal activiteiten' => 'aantal_activiteiten',
            'Aantal deelnemers' => 'aantal_deelnemers',
            'Aantal unieke deelnemers' => 'aantal_unieke_deelnemers',
        ];
        $grid = new Grid($data, $columns, 'stadsdeel');
        $grid
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setYNullReplacement('Onbekend')
        ;

        return $grid;
    }

    private function getVrijwilligersTotaalGrid(
        $class,
        \DateTime $startDate,
        \DateTime $endDate,
        $format = 'html'
    ) {
        $repository = $builder = $this->getEntityManager()->getRepository($class);

        $builder = $repository->createQueryBuilder('gaGroep')
            ->select('COUNT(DISTINCT vrijwilliger) AS aantal_vrijwilligers')
            ->innerJoin('gaGroep.gaVrijwilligerLeden', 'gaVrijwilligerLidmaatschap')
            ->innerJoin('gaVrijwilligerLidmaatschap.vrijwilliger', 'vrijwilliger')
            ->where('gaVrijwilligerLidmaatschap.startdatum <= :eind')
            ->andWhere('gaVrijwilligerLidmaatschap.einddatum > :start OR gaVrijwilligerLidmaatschap.einddatum IS NULL')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
        ;
        $data = $builder->getQuery()->getResult();

        $builder = $repository->createQueryBuilder('gaGroep')
            ->select('COUNT(DISTINCT vrijwilliger) AS aantal_nieuwe_vrijwilligers')
            ->innerJoin('gaGroep.gaVrijwilligerLeden', 'gaVrijwilligerLidmaatschap')
            ->innerJoin('gaVrijwilligerLidmaatschap.vrijwilliger', 'vrijwilliger')
            ->where('gaVrijwilligerLidmaatschap.startdatum BETWEEN :start AND :eind')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
        ;
        $data[0] = array_merge(current($data), current($builder->getQuery()->getResult()));

        $builder = $repository->createQueryBuilder('gaGroep')
            ->select('COUNT(DISTINCT vrijwilliger) AS aantal_gestopte_vrijwilligers')
            ->innerJoin('gaGroep.gaVrijwilligerLeden', 'gaVrijwilligerLidmaatschap')
            ->innerJoin('gaVrijwilligerLidmaatschap.vrijwilliger', 'vrijwilliger')
            ->where('gaVrijwilligerLidmaatschap.einddatum BETWEEN :start AND :eind')
            ->setParameter('start', $startDate)
            ->setParameter('eind', $endDate)
        ;
        $data[0] = array_merge(current($data), current($builder->getQuery()->getResult()));

        $columns = [
            'Aantal unieke vrijwilligers' => 'aantal_vrijwilligers',
            'Nieuw' => 'aantal_nieuwe_vrijwilligers',
            'Uitstroom/doorstroom' => 'aantal_gestopte_vrijwilligers',
        ];
        $grid = new Grid($data, $columns);
        $grid
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setYNullReplacement('Totaal')
        ;

        return $grid;
    }
}
