<?php

use AppBundle\Filter\FilterInterface;
use IzBundle\Form\IzKoppelingFilterType;
use IzBundle\Service\KoppelingDaoInterface;

class IzKoppelingenController extends AppController
{
    /**
     * Don't use CakePHP models.
     */
    public $uses = [];

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

    /**
     * @var KoppelingDaoInterface
     */
    private $koppelingDao;

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->koppelingDao = $this->container->get('IzBundle\Service\KoppelingDao');
    }

    public function index()
    {
        $form = $this->createFilter();
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('download')->isClicked()) {
                return $this->download($form->getData());
            }
        }

        $page = $this->getRequest()->get('page', 1);
        $pagination = $this->koppelingDao->findAll($page, $form->getData());

        $this->set('form', $form->createView());
        $this->set('pagination', $pagination);
    }

    public function download(FilterInterface $filter)
    {
        ini_set('memory_limit', '512M');

        $koppelingen = $this->koppelingDao->findAll(null, $filter);

        $this->autoRender = false;
        $filename = sprintf('iz-koppelingen-%s.xls', (new \DateTime())->format('d-m-Y'));

        $export = $this->container->get('iz.export.koppelingen');
        $export->create($koppelingen)->send($filename);
    }

    private function createFilter()
    {
        $form = $this->createForm(IzKoppelingFilterType::class);
        $form->handleRequest($this->getRequest());

        return $form;
    }
}
