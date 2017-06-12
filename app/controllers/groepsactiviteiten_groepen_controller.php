<?php

use GaBundle\Entity\GaGroep;
use GaBundle\Entity\GaKlantLidmaatschap;
use GaBundle\Entity\GaKlantIntake;
use IzBundle\Entity\IzDeelnemer;
use IzBundle\Entity\IzKlant;

class GroepsactiviteitenGroepenController extends AppController
{
    public $name = 'GroepsactiviteitenGroepen';

    public function index($showall = false)
    {
        $this->GroepsactiviteitenGroep->recursive = 0;

        $conditions = [
            'OR' => [
                'einddatum ' => null,
                'einddatum > ' => date('Y-m-d'),
            ],
        ];

        if (!empty($showall)) {
            $conditions = [
               'einddatum <= ' => date('Y-m-d'),
            ];
        }

        $this->paginate = [
            'conditions' => $conditions,
        ];

        $this->set('groepsactiviteitenGroepen', $this->paginate());
    }

    public function add()
    {
        if (!empty($this->data)) {
            $this->GroepsactiviteitenGroep->create();

            if ($this->GroepsactiviteitenGroep->save($this->data)) {
                $this->Session->setFlash(__('De groep is opgeslagen', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->Session->setFlash(__('Groep kan niet worden opgeslagen', true));
            }
        }

        $this->set('werkgebieden', Configure::read('Werkgebieden'));
    }

    public function edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Niet geldige groep', true));
            $this->redirect(['action' => 'index']);
        }

        if (!empty($this->data)) {
            $this->data['GroepsactiviteitenGroep']['id'] = $id;

            if ($this->GroepsactiviteitenGroep->save($this->data)) {
                $this->Session->setFlash(__('De groep is opgeslagen', true));
                $this->redirect(['action' => 'index']);
            } else {
                $this->Session->setFlash(__('Groep kan niet worden opgeslagen', true));
            }
        }
        if (empty($this->data)) {
            $this->GroepsactiviteitenGroep->recursive = 0;
            $this->data = $this->GroepsactiviteitenGroep->read(null, $id);
        }

        $this->set('werkgebieden', Configure::read('Werkgebieden'));
    }

    public function export($id, $persoon_model = 'Klant')
    {
        $this->autoLayout = false;
        $this->layout = false;

        $model = $this->name.$persoon_model;

        $groepsactiviteiten_list = $this->GroepsactiviteitenGroep->get_groepsactiviteiten_list();
        $groep = $groepsactiviteiten_list[$id];

        $this->loadModel($model);

        $params = [
            'contain' => [$persoon_model => ['Geslacht', 'GroepsactiviteitenIntake', 'IzDeelnemer']],
            'conditions' => [
                'groepsactiviteiten_groep_id' => $id,
                'OR' => [
                    'einddatum > NOW()',
                    'einddatum' => null,
                ],
            ],
        ];

//         $members = $this->getEntityManager()->getRepository(GaKlantLidmaatschap::class)
//             ->createQueryBuilder('gaKlantLidmaatschap')
//             ->innerJoin('gaKlantLidmaatschap.klant', 'klant')
//             ->leftJoin(GaKlantIntake::class, 'gaKlantIntake', 'WITH', 'gaKlantIntake.klant = klant')
//             ->leftJoin(IzKlant::class, 'izKlant', 'WITH', 'izKlant.klant = klant')
//             ->where('gaKlantLidmaatschap.gaGroep = :id')
//             ->andWhere('gaKlantLidmaatschap.einddatum IS NULL OR gaKlantLidmaatschap.einddatum > :now')
//             ->setParameter('id', $id)
//             ->setParameter('now', new \DateTime())
//             ->getQuery()
//             ->getResult()
//         ;

        $members = $this->{$model}->find('all', $params);

        $this->autoRender = false;
        $filename = "{$groep}_{$persoon_model}_lijst.xls";

        switch($persoon_model) {
            case 'Vrijwilliger':
                $export = $this->container->get('ga.export.groepsleden_vrijwilligers');
                break;
            default:
                $export = $this->container->get('ga.export.groepsleden_klanten');
                break;
        }

        $export->create($members)->send($filename);
    }
}
