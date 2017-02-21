<?php

use HsBundle\Entity\HsKlus;
use HsBundle\Form\HsKlusType;
use HsBundle\Entity\HsKlant;
use AppBundle\Form\ConfirmationType;
use AppBundle\Entity\Medewerker;
use HsBundle\Entity\HsMemo;
use HsBundle\Form\HsMemoType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class HsKlussenController extends AppController
{
    /**
     * Don't use CakePHP models.
     */
    public $uses = [];

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

    private $sortFieldWhitelist = [
        'hsKlus.id',
        'hsKlus.datum',
        'klant.achternaam',
        'klant.werkgebied',
        'hsActiviteit.naam',
        'vrijwilliger.achternaam',
    ];

    public function index()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsKlus::class);

        $builder = $repository->createQueryBuilder('hsKlus')
            ->innerJoin('hsKlus.hsKlant', 'hsKlant')
            ->innerJoin('hsKlus.hsActiviteit', 'hsActiviteit')
            ->innerJoin('hsKlant.klant', 'klant');

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'hsKlus.datum',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('pagination', $pagination);
    }

    public function download()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsKlus::class);

        $builder = $repository->createQueryBuilder('hsKlus')
            ->innerJoin('hsKlus.hsKlant', 'hsKlant')
            ->innerJoin('hsKlus.hsActiviteit', 'hsActiviteit')
            ->innerJoin('hsKlant.klant', 'klant');
        $hsKlussen = $builder->getQuery()->getResult();

        $now = new \DateTime();

        $this->autoLayout = false;
        $this->layout = false;
//         $this->header('Content-type: text/csv');
//         $this->header(sprintf('Content-Disposition: attachment; filename="homeservice-klussen-%s.csv";', $now->format('d-m-Y')));

        $this->set('now', $now);
        $this->set('hsKlussen', $hsKlussen);
    }

    public function view($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsKlus::class);
        $this->set('hsKlus', $repository->find($id));
    }

    public function add($hsKlantId = null)
    {
        $entityManager = $this->getEntityManager();

        $medewerkerId = $this->Session->read('Auth.Medewerker.id');
        $medewerker = $this->getEntityManager()->find(Medewerker::class, $medewerkerId);

        if ($hsKlantId) {
            $hsKlant = $entityManager->find(HsKlant::class, $hsKlantId);
            $hsKlus = new HsKlus($hsKlant, $medewerker);
        } else {
            $hsKlus = new HsKlus(null, $medewerker);
        }

        $form = $this->createForm(HsKlusType::class, $hsKlus);
        $form->add('memo', TextareaType::class, [
            'mapped' => false,
            'attr' => ['rows' => 10, 'cols' => 80],
        ]);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $hsMemo = new HsMemo($hsKlus->getMedewerker());
            $hsMemo->setMemo($form->get('memo')->getData());
            $hsKlus->addHsMemo($hsMemo);

            $entityManager->persist($hsKlus);
            $entityManager->flush();

            $this->Session->setFlash('Klus is opgeslagen.');

            return $this->redirect(array('action' => 'view', $hsKlus->getId()));
        }

        $this->set('form', $form->createView());
        if (isset($hsKlant)) {
            $this->set('hsKlant', $hsKlant);
        }
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsKlus::class);
        $hsKlus = $repository->find($id);

        $form = $this->createForm(HsKlusType::class, $hsKlus);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->flush();

            $this->Session->setFlash('Klus is opgeslagen.');

            return $this->redirect(array('action' => 'view', $hsKlus->getId()));
        }

        $this->set('form', $form->createView());
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(HsKlus::class);
        $hsKlus = $repository->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($hsKlus);
            $entityManager->flush();

            $this->Session->setFlash('Klus is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('hsKlus', $hsKlus);
        $this->set('form', $form->createView());
    }

    public function memos_index($hsKlusId)
    {
        $entityManager = $this->getEntityManager();
        $hsKlus = $entityManager->find(HsKlus::class, $hsKlusId);

        $builder = $entityManager->getRepository(HsMemo::class)->createQueryBuilder('hsMemo')
            ->innerJoin(HsKlus::class, 'hsKlus', 'WITH', 'hsKlus = :hsKlus')
            ->innerJoin('hsKlus.hsMemos', 'hsMemos', 'WITH', 'hsMemos = hsMemo')
            ->setParameter('hsKlus', $hsKlus)
        ;

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'hsMemo.datum',
            'defaultSortDirection' => 'desc',
            'sortFieldWhitelist' => ['hsMemo.datum'],
        ]);

        $this->set('hsKlus', $hsKlus);
        $this->set('pagination', $pagination);
    }

    public function memos_add($hsKlusId)
    {
        $entityManager = $this->getEntityManager();
        $hsKlus = $entityManager->find(HsKlus::class, $hsKlusId);

        $medewerkerId = $this->Session->read('Auth.Medewerker.id');
        $medewerker = $this->getEntityManager()->find(Medewerker::class, $medewerkerId);

        $hsMemo = new HsMemo($medewerker);

        $form = $this->createForm(HsMemoType::class, $hsMemo)->remove('intake');
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            try {
                $hsKlus->addHsMemo($hsMemo);
                $entityManager->flush();

                $this->Session->setFlash('Memo is opgeslagen.');
            } catch (\Exception $e) {
                $this->Session->setFlash('Er is een fout opgetreden.');
            } finally {
                return $this->redirect(array('action' => 'view', $hsKlus->getId()));
            }
        }

        $this->set('hsKlus', $hsKlus);
        $this->set('form', $form->createView());
    }
}
