<?php

use App\Entity\HsActiviteit;

class HsActiviteitenController extends AppController
{
	/**
	 * Don't use CakePHP models
	 */
	public $uses = [];

	/**
	 * Use Twig.
	 */
	public $view = 'AppTwig';

	public function index()
	{
		$entityManager = $this->getEntityManager();
		$repository = $entityManager->getRepository('App\Entity\HsActiviteit');
		$this->set('activiteiten', $repository->findAll());
	}

	public function view($id)
	{
		$entityManager = $this->getEntityManager();
		$repository = $entityManager->getRepository('App\Entity\HsActiviteit');
		$activiteit = $repository->find($id);
		$this->set('activiteit', $activiteit);
	}

	public function add()
	{
		$activiteit = new HsActiviteit();

		$form = $this->createForm('App\Form\HsActiviteitType', $activiteit);
		$form->handleRequest();

		if ($form->isValid()) {
			$entityManager = $this->getEntityManager();
			$entityManager->persist($activiteit);
			$entityManager->flush();

			$this->Session->setFlash('Activiteit is opgeslagen.');

			return $this->redirect(array('action' => 'index'));
		}

		$this->set('form', $form->createView());
	}

	public function edit($id)
	{
		$entityManager = $this->getEntityManager();
		$repository = $entityManager->getRepository('App\Entity\HsActiviteit');
		$activiteit = $repository->find($id);

		$form = $this->createForm('App\Form\HsActiviteitType', $activiteit);
		$form->handleRequest();

		if ($form->isValid()) {
			$entityManager->persist($activiteit);
			$entityManager->flush();

			$this->Session->setFlash('Activiteit is opgeslagen.');

			return $this->redirect(array('action' => 'index'));
		}

		$this->set('form', $form->createView());
	}

	public function delete($id)
	{
		$entityManager = $this->getEntityManager();
		$repository = $entityManager->getRepository('App\Entity\HsActiviteit');
		$activiteit = $repository->find($id);
		$this->set('activiteit', $activiteit);

		$form = $this->createForm('App\Form\ConfirmationType');
		$form->handleRequest();

		if ($form->isValid()) {
			$entityManager->remove($activiteit);
			$entityManager->flush();

			$this->Session->setFlash('Activiteit is verwijderd.');

			return $this->redirect(array('action' => 'index'));
		}

		$this->set('form', $form->createView());
	}
}
