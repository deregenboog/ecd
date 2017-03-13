<?php

namespace HsBundle\Controller;

use HsBundle\Entity\Klus;
use HsBundle\Form\KlusType;
use HsBundle\Entity\Klant;
use AppBundle\Form\ConfirmationType;
use AppBundle\Entity\Medewerker;
use HsBundle\Entity\Memo;
use HsBundle\Form\MemoType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use AppBundle\Controller\SymfonyController;
use Symfony\Component\Routing\Annotation\Route;
use HsBundle\Service\KlusDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use HsBundle\Form\KlusFilterType;

/**
 * @Route("/hs/klussen")
 */
class KlussenController extends SymfonyController
{
    use MemosAddControllerTrait;

    /**
     * @var KlusDaoInterface
     *
     * @DI\Inject("hs.dao.klus")
     */
    private $dao;

    private $enabledFilters = [
        'id',
        'datum',
        'activiteit',
        'klant' => ['naam', 'stadsdeel'],
        'filter',
        'download',
    ];

    /**
     * @Route("/")
     */
    public function index(Request $request)
    {
        $filter = $this->getFilter()->handleRequest($request);
        if ($filter->isSubmitted() && $filter->isValid()) {
            $pagination = $this->dao->findAll($request->get('page', 1), $filter->getData());
        } else {
            $pagination = $this->dao->findAll($request->get('page', 1));
        }

        return [
            'filter' => $filter->createView(),
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/download")
     */
    public function download()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Klus::class);

        $builder = $repository->createQueryBuilder('klus')
            ->innerJoin('klus.klant', 'klant')
            ->innerJoin('klus.activiteit', 'activiteit')
            ->innerJoin('klant.klant', 'klant');
        $klussen = $builder->getQuery()->getResult();

        $now = new \DateTime();

        $this->autoLayout = false;
        $this->layout = false;
        $this->header('Content-type: text/csv');
        $this->header(sprintf('Content-Disposition: attachment; filename="homeservice-klussen-%s.csv";', $now->format('d-m-Y')));

        $this->set('now', $now);
        $this->set('klussen', $klussen);
    }

    /**
     * @Route("/{id}/view")
     */
    public function view($id)
    {
        $entity = $this->dao->find($id);

        return [
            'entity' => $entity,
        ];
    }

    /**
     * @Route("/add")
     */
    public function add($klantId = null)
    {
        $entityManager = $this->getEntityManager();

        $medewerkerId = $this->Session->read('Auth.Medewerker.id');
        $medewerker = $this->getEntityManager()->find(Medewerker::class, $medewerkerId);

        if ($klantId) {
            $klant = $entityManager->find(Klant::class, $klantId);
            $klus = new Klus($klant, $medewerker);
        } else {
            $klus = new Klus(null, $medewerker);
        }

        $form = $this->createForm(KlusType::class, $klus);
        $form->add('memo', TextareaType::class, [
            'mapped' => false,
            'attr' => ['rows' => 10, 'cols' => 80],
        ]);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $memo = new Memo($klus->getMedewerker());
            $memo->setMemo($form->get('memo')->getData());
            $klus->addMemo($memo);

            $entityManager->persist($klus);
            $entityManager->flush();

            $this->addFlash('success', 'Klus is opgeslagen.');

            return $this->redirect(array('action' => 'view', $klus->getId()));
        }

        $this->set('form', $form->createView());
        if (isset($klant)) {
            $this->set('klant', $klant);
        }
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $klus = $this->dao->find($id);

        $form = $this->createForm(KlusType::class, $klus);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dao->update($klus);

            $this->addFlash('success', 'Klus is opgeslagen.');

            return $this->redirectToView($klus);
        }

        $this->set('form', $form->createView());
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository(Klus::class);
        $klus = $repository->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($klus);
            $entityManager->flush();

            $this->addFlash('success', 'Klus is verwijderd.');

            return $this->redirect(array('action' => 'index'));
        }

        $this->set('klus', $klus);
        $this->set('form', $form->createView());
    }

    private function getFilter()
    {
        return $this->createForm(KlusFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
    }

    private function redirectToView($entity)
    {
        return $this->redirectToRoute('hs_klussen_view', ['id' => $entity->getId()]);
    }
}
