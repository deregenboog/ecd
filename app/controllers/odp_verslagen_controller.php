<?php

use AppBundle\Entity\Klant;
use OdpBundle\Entity\OdpHuuraanbod;
use OdpBundle\Entity\OdpHuurovereenkomst;
use OdpBundle\Entity\OdpHuurder;
use OdpBundle\Entity\OdpVerslag;
use OdpBundle\Form\OdpVerslagType;
use OdpBundle\Entity\OdpVerhuurder;
use OdpBundle\Exception\OdpException;
use OdpBundle\Entity\OdpHuurverzoek;
use Doctrine\ORM\EntityManager;

class OdpVerslagenController extends AppController
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
        'klant' => ['naam', 'stadsdeel'],
        'startdatum',
        'einddatum',
    ];

    private $sortFieldWhitelist = [
        'odpHuuraanbod.id',
        'klant.achternaam',
        'klant.werkgebied',
        'odpHuuraanbod.startdatum',
        'odpHuuraanbod.einddatum',
    ];

    public function add()
    {
        $entityManager = $this->getEntityManager();
        $entity = $this->findEntity($entityManager);

        $form = $this->createForm(OdpVerslagType::class, new OdpVerslag());
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $controller = $this->resolveController($entity);
            try {
                $entityManager->persist($entity->addOdpVerslag($form->getData()));
                $entityManager->flush();
                $this->flash('Verslag is toegevoegd.');
            } catch (\Exception $e) {
                $this->flashError('Er is een fout opgetreden.');

                return $this->redirect(['controller' => $controller, 'action' => 'index']);
            }

            return $this->redirect(['controller' => $controller, 'action' => 'view', $entity->getId()]);
        }

        $this->set('form', $form->createView());
    }

    private function findEntity(EntityManager $entityManager)
    {
        switch (true) {
            case isset($this->params['named']['odpHuurder']):
                $entity = $entityManager->find(OdpHuurder::class, $this->params['named']['odpHuurder']);
                break;
            case isset($this->params['named']['odpVerhuurder']):
                $entity = $entityManager->find(OdpVerhuurder::class, $this->params['named']['odpVerhuurder']);
                break;
            case isset($this->params['named']['odpHuurverzoek']):
                $entity = $entityManager->find(OdpHuurverzoek::class, $this->params['named']['odpHuurverzoek']);
                break;
            case isset($this->params['named']['odpHuuraanbod']):
                $entity = $entityManager->find(OdpHuuraanbod::class, $this->params['named']['odpHuuraanbod']);
                break;
            case isset($this->params['named']['odpHuurovereenkomst']):
                $entity = $entityManager->find(OdpHuurovereenkomst::class, $this->params['named']['odpHuurovereenkomst']);
                break;
            default:
                throw new OdpException('Kan geen verslag aan deze entiteit toevoegen');
        }

        return $entity;
    }

    private function resolveController($entity)
    {
        switch (true) {
            case $entity instanceof OdpHuurder:
                $controller = 'odp_huurders';
                break;
            case $entity instanceof OdpVerhuurder:
                $controller = 'odp_verhuurders';
                break;
            case $entity instanceof OdpHuurverzoek:
                $controller = 'odp_huurverzoeken';
                break;
            case $entity instanceof OdpHuuraanbod:
                $controller = 'odp_huuraanbiedingen';
                break;
            case $entity instanceof OdpHuurovereenkomst:
                $controller = 'odp_huurovereenkomsten';
                break;
            default:
                throw new OdpException('Kan geen verslag aan deze entiteit toevoegen');
        }

        return $controller;
    }
}
