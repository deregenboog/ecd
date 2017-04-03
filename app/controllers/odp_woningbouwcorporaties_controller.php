<?php

use AppBundle\Entity\Klant;
use OdpBundle\Entity\OdpHuurder;
use OdpBundle\Entity\OdpHuurovereenkomst;
use OdpBundle\Form\OdpHuurovereenkomstFilterType;
use OdpBundle\Form\OdpHuurovereenkomstType;
use OdpBundle\Entity\OdpWoningbouwcorporatie;
use OdpBundle\Service\OdpWoningbouwcorporatieDaoInterface;

class OdpWoningbouwcorporatiesController extends AppController
{
    /**
     * Don't use CakePHP models.
     */
    public $uses = [];

    /**
     * Use Twig.
     */
    public $view = 'AppTwig';

    private $sortFieldWhitelist = [
        'odpWoningbouwcorporatie.id',
        'odpWoningbouwcorporatie.naam',
        'odpWoningbouwcorporatie.actief',
    ];

    /**
     * @var OdpWoningbouwcorporatieDaoInterface
     *
     * @DI\Inject("odp.dao.odp_woningbouwcorporatie")
     */
    private $odpWoningbouwcorporatieDao;

    public function index()
    {
        var_dump($this->odpWoningbouwcorporatieDao); die;
        $dao = $this->container->get('odp.dao.odp_woningbouwcorporatie');

        $pagination = $dao->findAll($this->getRequest()->get('page', 1), [
            'defaultSortFieldName' => 'odpWoningbouwcorporatie.naam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
        ]);

        $this->set('pagination', $pagination);
    }

    public function edit($odpHuurovereenkomstId)
    {
        $entityManager = $this->getEntityManager();
        $odpHuurovereenkomst = $entityManager->find(OdpHuurovereenkomst::class, $odpHuurovereenkomstId);

        $form = $this->createForm(OdpHuurovereenkomstType::class, $odpHuurovereenkomst);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->flashError('Er is een fout opgetreden.');
            }

            return $this->redirect(['controller' => 'odp_huurovereenkomsten', 'action' => 'view', $odpHuurovereenkomst->getId()]);
        }

        $this->set('form', $form->createView());
    }
}
