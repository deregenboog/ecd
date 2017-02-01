<?php

use OekBundle\Entity\OekTraining;
use OekBundle\Form\Model\OekTrainingModel;
use OekBundle\Form\OekTrainingFilterType;
use OekBundle\Form\OekTrainingKlantType;
use OekBundle\Form\OekTrainingType;
use AppBundle\Form\ConfirmationType;
use Symfony\Component\Form\Test\FormInterface;

class OekTrainingenController extends AppController
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
        'id',
        'naam',
        'oekGroep',
        'startdatum',
        'einddatum'
    ];

    private $sortFieldWhitelist = [
        'oekTraining.id',
        'oekTraining.naam',
        'oekGroep.naam',
        'oekTraining.startdatum',
        'oekTraining.einddatum'
    ];

    public function index()
    {
        /** @var FormInterface $filter */
        $filter = $this->createForm(OekTrainingFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);

        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekTraining::class);

        $builder = $repository->createQueryBuilder('oekTraining')
            ->leftJoin('oekTraining.oekKlanten', 'oekKlanten')
            ->innerJoin('oekTraining.oekGroep', 'oekGroep');

        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->request->get('page', 1), 20, [
            'defaultSortFieldName' => 'oekTraining.startdatum',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('filter', $filter->createView());
        $this->set('pagination', $pagination);
    }

    public function view($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekTraining::class);
        $this->set('oekTraining', $repository->find($id));
    }

    public function add()
    {
        $entityManager = $this->getEntityManager();

        $oekTraining = new OekTraining();

        $form = $this->createForm(OekTrainingType::class, $oekTraining);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->persist($oekTraining);
            $entityManager->flush();

            $this->Session->setFlash('Training is opgeslagen.');

            return $this->redirect(array('action' => 'view', $oekTraining->getId()));
        }

        $this->set('form', $form->createView());
    }

    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekTraining::class);
        $oekTraining = $repository->find($id);

        $form = $this->createForm(OekTrainingType::class, $oekTraining);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->flush();

            $this->Session->setFlash('Training is opgeslagen.');

            return $this->redirect(array('action' => 'view', $oekTraining->getId()));
        }

        $this->set('form', $form->createView());
    }

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekTraining::class);
        $oekTraining = $repository->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->remove($oekTraining);
            $entityManager->flush();

            $this->Session->setFlash('Training is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('oekTraining', $oekTraining);
        $this->set('form', $form->createView());
    }

    public function voeg_klant_toe($oekTrainingId)
    {
        /** @var OekTraining $oekTraining */
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekTraining::class);
        $oekTraining = $repository->find($oekTrainingId);
        $oekTrainingModel = new OekTrainingModel($oekTraining);

        $form = $this->createForm(OekTrainingKlantType::class, $oekTrainingModel);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            $entityManager->flush();

            $this->Session->setFlash('Klant is toegevoegd aan groep.');

            return $this->redirect(array('action' => 'view', $oekTraining->getId()));
        }

        $this->set('form', $form->createView());
    }

    public function email_deelnemers($oekTrainingId)
    {
        if ($this->request->server->get('REQUEST_METHOD') != 'POST') {
            return;
        }

        /** @var OekTraining $oekTraining */
        /** @var Swift_Mailer $mailer */
        /** @var Swift_Mime_Message $message */
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(OekTraining::class);
        $oekTraining = $repository->find($oekTrainingId);

        $mailer = $this->container->get('mailer');
        $message = $mailer->createMessage();

        $message->setSubject($this->request->get('subject'));
        $message->setBody($this->request->get('body'));

        $addresses = [];

        foreach($oekTraining->getOekKlanten() as $oekKlant) {
            $klant = $oekKlant->getKlant();
            $addresses[$klant->getEmail()] = $klant->getNaam();
        }

        $message->setTo($addresses);

        $failedRecipients = [];
        $mailer->getTransport()->send($message, $failedRecipients);

        if ($failedRecipients) {
            $failedRecipients = implode(', ', $failedRecipients);
            $this->Session->setFlash(
                'De email kon niet worden verzonden naar: ' . $failedRecipients
            );
        } else {
            $this->Session->setFlash('De email is succesvol verzonden.');
        }

        return $this->redirect(array('action' => 'view', $oekTraining->getId()));
    }
}
