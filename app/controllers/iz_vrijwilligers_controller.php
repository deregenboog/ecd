<?php

use AppBundle\Entity\Vrijwilliger;
use AppBundle\Filter\FilterInterface;
use AppBundle\Form\VrijwilligerFilterType;
use AppBundle\Form\VrijwilligerType;
use IzBundle\Form\IzVrijwilligerFilterType;
use IzBundle\Service\VrijwilligerDaoInterface;

class IzVrijwilligersController extends AppController
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
     * @var VrijwilligerDaoInterface
     */
    private $vrijwilligerDao;

    public function beforeFilter()
    {
        return $this->redirect('/iz/vrijwilligers');

        parent::beforeFilter();
        $this->vrijwilligerDao = $this->container->get('IzBundle\Service\VrijwilligerDao');
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
        $pagination = $this->vrijwilligerDao->findAll($page, $form->getData());

        $this->set('form', $form->createView());
        $this->set('pagination', $pagination);
    }

    public function download(FilterInterface $filter)
    {
        ini_set('memory_limit', '512M');

        $vrijwilligers = $this->vrijwilligerDao->findAll(null, $filter);

        $this->autoRender = false;
        $filename = sprintf('iz-vrijwilligers-%s.xls', (new \DateTime())->format('d-m-Y'));

        $export = $this->container->get('iz.export.vrijwilligers');
        $export->create($vrijwilligers)->send($filename);
    }

    public function add($vrijwilligerId = null)
    {
        if ($vrijwilligerId) {
            if ('new' === $vrijwilligerId) {
                $creationForm = $this->createForm(VrijwilligerType::class);
                $creationForm->handleRequest($this->getRequest());
                if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                    $vrijwilliger = $creationForm->getData();
                    $this->getEntityManager()->persist($vrijwilliger);
                    $this->getEntityManager()->flush();

                    return $this->redirect([
                        'controller' => 'iz_deelnemers',
                        'action' => 'toon_aanmelding',
                        'Vrijwilliger',
                        $vrijwilliger->getId(),
                    ]);
                }
                $this->set('creationForm', $creationForm->createView());

                return;
            } else {
                return $this->redirect([
                    'controller' => 'iz_deelnemers',
                    'action' => 'toon_aanmelding',
                    'Vrijwilliger',
                    $vrijwilligerId,
                ]);
            }
        }

        $filterForm = $this->createForm(VrijwilligerFilterType::class, null, [
            'enabled_filters' => ['naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($this->getRequest());

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $builder = $this->getEntityManager()->getRepository(Vrijwilliger::class)
                ->createQueryBuilder('vrijwilliger')
                ->orderBy('vrijwilliger.achternaam')
            ;
            $filterForm->getData()->applyTo($builder);
            $this->set('vrijwilligers', $builder->getQuery()->getResult());

            return;
        }

        $this->set('filterForm', $filterForm->createView());
    }

    private function createFilter()
    {
        $form = $this->createForm(IzVrijwilligerFilterType::class);
        $form->handleRequest($this->getRequest());

        return $form;
    }
}
