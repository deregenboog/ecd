<?php

use App\Entity\Vrijwilliger;
use App\Entity\HsVrijwilliger;
use App\Form\HsVrijwilligerType;
use Symfony\Component\Form\FormError;
use Doctrine\DBAL\DBALException;

class HsVrijwilligersController extends AppController
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
        $entityManager = Registry::getInstance()->getManager();
        $repository = $entityManager->getRepository(HsVrijwilliger::class);
        $this->set('hsVrijwilligers', $repository->findAll());
    }

    public function view($id)
    {
        $entityManager = Registry::getInstance()->getManager();
        $hsVrijwilliger = $entityManager->find(HsVrijwilliger::class, $id);
        $this->set('hsVrijwilliger', $hsVrijwilliger);
    }

    public function add()
    {
        $hsVrijwilliger = new HsVrijwilliger();

        $form = $this->createForm(HsVrijwilligerType::class, $hsVrijwilliger);
        $form->handleRequest();

        if ($form->isValid()) {
            try {
                $entityManager = $this->getEntityManager();
                $entityManager->persist($hsVrijwilliger);
                $entityManager->flush();
                $this->Session->setFlash('Vrijwilliger is opgeslagen.');

                return $this->redirect(['action' => 'index']);
            } catch (DBALException $e) {
                if ($e->getPrevious() instanceof PDOException
                       && $e->getPrevious()->getCode() == 23000
                ) {
                    $form->addError(new FormError('Deze vrijwilliger heeft al een Homeservice-dossier.'));
                } else {
                    $form->addError(new FormError('Er is een fout opgetreden.'));
                }
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        $this->set('form', $form->createView());
    }

    public function edit($id)
    {
        $entityManager = Registry::getInstance()->getManager();
        $hsVrijwilliger = $entityManager->find(HsVrijwilliger::class, $id);

        $form = $this->createForm(HsVrijwilligerType::class, $hsVrijwilliger);
        $form->handleRequest();

        if ($form->isValid()) {
            try {
                $entityManager->flush();
                $this->Session->setFlash('Vrijwilliger is opgeslagen.');

                return $this->redirect(['action' => 'index']);
            } catch (\Exception $e) {
                $form->addError(new FormError('Er is een fout opgetreden.'));
            }
        }

        $this->set('form', $form->createView());
    }
}
