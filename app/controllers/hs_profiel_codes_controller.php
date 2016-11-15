<?php

use App\Entity\HsProfielCode;
use App\Form\HsProfielCodeType;
use App\Form\ConfirmationType;

class HsProfielCodesController extends AppController
{
	/**
	 * Don't use CakePHP models
	 */
	public $uses = [];

	/**
	 * Use Twig.
	 */
	public $view = 'AppTwig';

    public function view($id)
    {
    	$entityManager = Registry::getInstance()->getManager();
    	$hsProfielCode = $entityManager->find(HsProfielCode::class, $id);
    	$this->set('hsProfielCode', $hsProfielCode);
    }

    public function edit($id)
    {
    	$entityManager = Registry::getInstance()->getManager();
    	$hsProfielCode = $entityManager->find(HsProfielCode::class, $id);

    	$form = $this->createForm(HsProfielCodeType::class, $hsProfielCode);
    	$form->handleRequest();

    	if ($form->isValid()) {
    		$entityManager = Registry::getInstance()->getManager();
    		$entityManager->flush();

    		if ($hsKlant = $hsProfielCode->getHsKlant()) {
    			return $this->redirect(['controller' => 'hs_klanten', 'action' => 'view', $hsKlant->getId()]);
    		}
    	}

    	$this->set('form', $form->createView());
    	$this->set('data', $form->getData());
    }

    public function delete($id)
    {
    	$entityManager = Registry::getInstance()->getManager();
    	$hsProfielCode = $entityManager->find(HsProfielCode::class, $id);

    	$form = $this->createForm(ConfirmationType::class);
    	$form->handleRequest();

    	if ($form->isValid()) {
    		$entityManager->remove($hsProfielCode);
    		$entityManager->flush();

    		if ($hsKlant = $hsProfielCode->getHsKlant()) {
    			return $this->redirect(['controller' => 'hs_klanten', 'action' => 'view', $hsKlant->getId()]);
    		}
    	}

    	$this->set('form', $form->createView());
    	$this->set('hsProfielCode', $hsProfielCode);
    }
}

