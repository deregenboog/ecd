<?php

use OekBundle\Entity\OekTraining;
use OekBundle\Form\OekTrainingFilterType;
use OekBundle\Form\OekTrainingType;
use AppBundle\Form\ConfirmationType;
use OekBundle\Form\OekEmailMessageType;
use OekBundle\Entity\OekGroep;

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
        'einddatum',
    ];

    private $sortFieldWhitelist = [
        'oekTraining.id',
        'oekTraining.naam',
        'oekGroep.naam',
        'oekTraining.startdatum',
        'oekTraining.einddatum',
    ];

    public function index()
    {
        $repository = $this->getEntityManager()->getRepository(OekTraining::class);
        $builder = $repository->createQueryBuilder('oekTraining')
            ->leftJoin('oekTraining.oekKlanten', 'oekKlanten')
            ->innerJoin('oekTraining.oekGroep', 'oekGroep');

        $filter = $this->createForm(OekTrainingFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->request);
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
        if (isset($this->params['named']['oekGroep'])) {
            $oekGroep = $entityManager->find(OekGroep::class, $this->params['named']['oekGroep']);
            $oekTraining->setOekGroep($oekGroep);
        }

        $form = $this->createForm(OekTrainingType::class, $oekTraining);
        $form->handleRequest($this->request);
        if ($form->isValid()) {
            $entityManager->persist($oekTraining);
            $entityManager->flush();

            $this->Session->setFlash('Training is opgeslagen.');

            return $this->redirect(['action' => 'view', $oekTraining->getId()]);
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

    public function email_deelnemers($oekTrainingId)
    {
        /** @var OekTraining $oekTraining */
        $oekTraining = $this->getEntityManager()->getRepository(OekTraining::class)
            ->find($oekTrainingId);

        $form = $this->createForm(OekEmailMessageType::class, null, [
            'from' => $this->Session->read('Auth.Medewerker.LdapUser.mail'),
            'to' => $oekTraining->getOekKlanten(),
        ]);
        $form->handleRequest($this->request);

        if ($form->isValid()) {
            /** @var Swift_Mailer $mailer */
            $mailer = $this->container->get('mailer');

            /** @var Swift_Mime_Message $message */
            $message = $mailer->createMessage()
                ->setFrom($form->get('from')->getData())
                ->setTo($form->get('from')->getData())
                ->setBcc(explode(', ', $form->get('to')->getData()))
                ->setSubject($form->get('subject')->getData())
                ->setBody($form->get('text')->getData(), 'text/plain')
            ;

            if ($mailer->send($message)) {
                $this->flash(__('Email is succesvol verzonden', true));
            } else {
                $this->flashError(__('Email kon niet worden verzonden', true));
            }

            return $this->redirect([
                'action' => 'view',
                $oekTraining->getId(),
            ]);
        }

        $this->set('form', $form->createView());
        $this->set('oekTraining', $oekTraining);
    }
}
