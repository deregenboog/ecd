<?php

use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Doctrine\Common\Annotations\AnnotationRegistry;
use App\Entity\Klant;
use App\Entity\HsKlant;
use App\Form\HsKlantType;
use App\Entity\HsProfielGroep;
use App\Form\HsProfielGroepType;
use App\Form\ConfirmationType;

class HsProfielGroepenController extends AppController
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
        $entityManager = Registry::getInstance()->getManager();
        $repository = $entityManager->getRepository(HsProfielGroep::class);
        $this->set('hsProfielGroepen', $repository->findAll());
    }

    public function view($id)
    {
    	$entityManager = Registry::getInstance()->getManager();
    	$hsProfielGroep = $entityManager->find(HsProfielGroep::class, $id);
    	$this->set('hsProfielGroep', $hsProfielGroep);
    }

    public function add()
    {
    	$hsProfielGroep = new HsProfielGroep();

    	$form = $this->createForm(HsProfielGroepType::class, $hsProfielGroep);
        $form->handleRequest();

        if ($form->isValid()) {
        	$entityManager = Registry::getInstance()->getManager();
        	$entityManager->persist($hsProfielGroep);
        	$entityManager->flush();

            return $this->redirect(['action' => 'index']);
        }

        $this->set('form', $form->createView());
    }

    public function edit($id)
    {
    	$entityManager = Registry::getInstance()->getManager();
    	$hsProfielGroep = $entityManager->find(HsProfielGroep::class, $id);

    	$form = $this->createForm(HsProfielGroepType::class, $hsProfielGroep);
    	$form->handleRequest();

    	if ($form->isValid()) {
    		$entityManager = Registry::getInstance()->getManager();
    		$entityManager->flush();

    		return $this->redirect(['action' => 'index']);
    	}

    	$this->set('form', $form->createView());
    }

    public function delete($id)
    {
    	$entityManager = Registry::getInstance()->getManager();
    	$hsProfielGroep = $entityManager->find(HsProfielGroep::class, $id);

    	$form = $this->createForm(ConfirmationType::class);
    	$form->handleRequest();

    	if ($form->isValid()) {
    		$entityManager->remove($hsProfielGroep);
    		$entityManager->flush();

    		return $this->redirect(['action' => 'index']);
    	}

    	$this->set('form', $form->createView());
    	$this->set('hsProfielGroep', $hsProfielGroep);
    }
}

