<?php

use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Doctrine\Common\Annotations\AnnotationRegistry;
use App\Entity\Klant;
use App\Entity\HsRegistratie;
use App\Form\HsRegistratieType;
use App\Entity\HsKlus;
use App\Entity\HsVrijwilliger;
use Symfony\Component\Form\FormView;

class HsRegistratiesController extends AppController
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
        $repository = $entityManager->getRepository('App\Entity\HsKlant');
        $this->set('klanten', $repository->findAll());
    }

    public function add($hsKlusId, $hsVrijwilligerId)
    {
    	$entityManager = Registry::getInstance()->getManager();
    	$hsKlus = $entityManager->find(HsKlus::class, $hsKlusId);
    	$hsVrijwilliger = $entityManager->find(HsVrijwilliger::class, $hsVrijwilligerId);

    	$form = $this->createForm(HsRegistratieType::class, new HsRegistratie($hsKlus, $hsVrijwilliger));
        $form->handleRequest();

        if ($form->isValid()) {
        	$entityManager->persist($form->getData());
        	$entityManager->flush();

            return $this->redirect([
            	'controller' => 'hs_klussen',
            	'action' => 'view',
            	$hsKlus->getId(),
            ]);
        }

        $this->set('form', $form->createView());
    }

    public function edit($id)
    {
    	$entityManager = Registry::getInstance()->getManager();
    	$hsRegistratie = $entityManager->find(HsRegistratie::class, $id);

    	$form = $this->createForm(HsRegistratieType::class, $hsRegistratie);
    	$form->handleRequest();

    	if ($form->isValid()) {
    		$entityManager->flush();

    		return $this->redirect([
				'controller' => 'hs_klussen',
				'action' => 'view',
    			$hsRegistratie->getHsKlus()->getId(),
    		]);
    	}

    	$this->set('form', $form->createView());
    }

//     public function view($id)
//     {
//         $klant = $this->HsKlant->findById($id);
//         $this->set('klant', $klant);
//     }
}
