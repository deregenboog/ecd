<?php

use AppBundle\Entity\Klant;
use OdpBundle\Entity\OdpHuuraanbod;
use OdpBundle\Form\OdpHuuraanbodType;
use AppBundle\Form\KlantFilterType;
use OdpBundle\Form\OdpHuuraanbodSelectType;
use Doctrine\DBAL\Driver\PDOException;
use OdpBundle\Form\OdpHuuraanbodFilterType;
use AppBundle\Form\ConfirmationType;
use AppBundle\Entity\Medewerker;
use OdpBundle\Entity\HsMemo;
use OdpBundle\Form\HsMemoType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class OdpHuuraanbiedingenController extends AppController
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
    ];

    private $sortFieldWhitelist = [
        'odpHuuraanbod.id',
        'klant.achternaam',
        'klant.werkgebied',
    ];

    public function index()
    {
        $filter = $this->createForm(OdpHuuraanbodFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OdpHuuraanbod::class);

        $builder = $repository->createQueryBuilder('odpHuuraanbod')
            ->innerJoin('odpHuuraanbod.odpVerhuurder', 'odpVerhuurder')
            ->innerJoin('odpVerhuurder.klant', 'klant');

        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
//             'defaultSortFieldName' => 'klant.achternaam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('filter', $filter->createView());
        $this->set('pagination', $pagination);
    }

    public function view($id)
    {
        $entityManager = $this->getEntityManager();
        $odpHuuraanbod = $entityManager->find(OdpHuuraanbod::class, $id);
        $this->set('odpHuuraanbod', $odpHuuraanbod);
        $this->set('odpVerhuurder', $odpHuuraanbod->getOdpVerhuurder());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $odpHuuraanbod = $entityManager->find(OdpHuuraanbod::class, $id);

        $form = $this->createForm(OdpHuuraanbodType::class, $odpHuuraanbod);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            try {
                $entityManager->flush();

                $this->Session->setFlash('Klant is opgeslagen.');

                return $this->redirect(array('action' => 'index'));
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        $this->set('form', $form->createView());
        $this->set('odpHuuraanbod', $odpHuuraanbod);
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $odpHuuraanbod = $entityManager->find(OdpHuuraanbod::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($odpHuuraanbod);
            $entityManager->flush();

            $this->Session->setFlash('Klant is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('odpHuuraanbod', $odpHuuraanbod);
        $this->set('form', $form->createView());
    }
}
