<?php

use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmExtension;
use Doctrine\Common\Annotations\AnnotationRegistry;
use App\Entity\Klant;
use App\Entity\HsKlant;
use App\Form\HsKlantType;
use App\Entity\HsProfielCode;
use App\Form\HsProfielCodeType;

class HsKlantenController extends AppController
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
        $repository = $entityManager->getRepository(HsKlant::class);
        $this->set('klanten', $repository->findAll());
    }

    public function view($id)
    {
    	$entityManager = Registry::getInstance()->getManager();
    	$hsKlant = $entityManager->find(HsKlant::class, $id);
    	$this->set('hsKlant', $hsKlant);
    }

    public function add()
    {
    	$hsKlant = new HsKlant();

        $form = $this->createForm(HsKlantType::class, $hsKlant);
        $form->handleRequest();

        if ($form->isValid()) {
        	return $this->add2($form->getData()->getKlant());
//         	$form2 = $this->createForm(HsKlantType::class, $hsKlant, [
//         		'mode' => HsKlantType::MODE_SELECT,
//         		'select_filter' => $form->getData()->getKlant(),
//         	]);
//         	$this->set('form', $form2->createView());
//         	return;

//         	$form2->handleRequest();
        }

        $this->set('form', $form->createView());
    }

    public function add2(Klant $filter = null)
    {
    	$hsKlant = new HsKlant();

    	 $form = $this->createForm(HsKlantType::class, $hsKlant, [
    			'mode' => HsKlantType::MODE_SELECT,
    			'filter' => $filter,
    	 		'action' => 'add2',
    	]);
    	$this->set('form', $form->createView());

    	return $this->render('add');
    }

    public function edit($id)
    {
    	$form = $this->createForm(HsKlantType::class, new App\Entity\HsKlant());
    	$form->handleRequest();

    	if ($form->isValid()) {
    		return $this->redirect(['action' => 'add2']);
    	}

    	$this->set('form', $form->createView());
    }

    public function deleten($id)
    {
    	$form = $this->createForm(HsKlantType::class, new App\Entity\HsKlant());
    	$form->handleRequest();

    	if ($form->isValid()) {
    		return $this->redirect(['action' => 'add2']);
    	}

    	$this->set('form', $form->createView());
    }

    public function add_hs_profiel_code($hsKlantId)
    {
    	$hsKlant = $this->getHsKlant($hsKlantId);
    	$hsProfielCode = new HsProfielCode();

    	$form = $this->createForm(HsProfielCodeType::class, $hsProfielCode);
    	$form->handleRequest();

    	if ($form->isValid()) {
    		$hsKlant->addHsProfielCode($hsProfielCode);
    		$this->getEntityManager()->flush();

    		return $this->redirect(['action' => 'view', $hsKlant->getId()]);
    	}

    	$this->set('form', $form->createView());
    	$this->set('hsKlant', $hsKlant);
   	}

   	protected function getHsKlant($hsKlantId)
    {
    	return $this->getEntityManager()->find(HsKlant::class, $hsKlantId);
    }
}

