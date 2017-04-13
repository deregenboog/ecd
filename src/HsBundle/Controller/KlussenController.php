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
use HsBundle\Entity\Dienstverlener;
use HsBundle\Entity\Vrijwilliger;
use HsBundle\Service\VrijwilligerDaoInterface;
use HsBundle\Service\DienstverlenerDaoInterface;
use HsBundle\Form\VrijwilligerSelectType;
use HsBundle\Form\DienstverlenerSelectType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Filter\FilterInterface;
use HsBundle\Service\KlantDaoInterface;

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

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("hs.dao.klant")
     */
    private $klantDao;

    /**
     * @var DienstverlenerDaoInterface
     *
     * @DI\Inject("hs.dao.dienstverlener")
     */
    private $dienstverlenerDao;

    /**
     * @var VrijwilligerDaoInterface
     *
     * @DI\Inject("hs.dao.vrijwilliger")
     */
    private $vrijwilligerDao;

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
        $form = $this->getFilter()->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filter = $form->getData();
        } else {
            $filter = null;
        }

        if ($form->get('download')->isClicked()) {
            return $this->download($filter);
        }

        $pagination = $this->dao->findAll($request->get('page', 1), $filter);

        return [
            'filter' => $form->createView(),
            'pagination' => $pagination,
        ];
    }

    private function download(FilterInterface $filter)
    {
        $collection = $this->dao->findAll(0, $filter);

        $response = $this->render('@Hs/klussen/download.csv.twig', ['collection' => $collection]);

        $filename = sprintf('homeservice-klussen-%s.xls', (new \DateTime())->format('d-m-Y'));
        $response->headers->set('Content-type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
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
    public function add(Request $request)
    {
        $medewerker = $this->getMedewerker();
        $klant = null;
        if ($request->query->has('klantId')) {
            $klant = $this->klantDao->find($request->query->get('klantId'));
        }
        $klus = new Klus($klant, $medewerker);

        $form = $this->createForm(KlusType::class, $klus);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->create($klus);
                $this->addFlash('success', 'Klus is opgeslagen.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToView($klus);
        }

        $params = ['form' => $form->createView()];
        if ($klant) {
            $params['klant'] = $klant;
        }

        return $params;
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(KlusType::class, $entity);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $this->dao->update($entity);
                $this->addFlash('success', 'Klus is opgeslagen.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToView($entity);
        }

        return [
            'klus' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $this->dao->delete($entity);
                    $this->addFlash('success', 'Klus is verwijderd.');
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Er is een fout opgetreden.');
                }
            }

            return $this->redirectToIndex();
        }

        return [
            'klus' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/dienstverleners/add")
     */
    public function dienstverleners_add($id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createFormBuilder($entity)
            ->add('dienstverleners', null, [
                'mapped' => false,
                'query_builder' => function (EntityRepository $repository) use ($entity) {
                    return $repository->createQueryBuilder('dienstverlener')
                        ->leftJoin('dienstverlener.klussen', 'klus', 'WITH', 'klus = :klus')
                        ->where('klus.id IS NULL')
                        ->setParameter('klus', $entity)
                    ;
                },
            ])
            ->add('submit', SubmitType::class)
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->get('dienstverleners')->getData() as $dienstverlener) {
                $entity->addDienstverlener($dienstverlener);
            }

            try {
                $this->dao->update($entity);
                $this->addFlash('success', 'Dienstverlener is toegevoegd.');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{klusId}/dienstverleners/{dienstverlenerId}/delete")
     */
    public function dienstverleners_delete($klusId, $dienstverlenerId)
    {
        $entity = $this->dao->find($klusId);
        $dienstverlener = $this->dienstverlenerDao->find($dienstverlenerId);

        $entity->getDienstverleners()->removeElement($dienstverlener);
        $this->dao->update($entity);

        return $this->redirectToView($entity);
    }

    /**
     * @Route("/{id}/vrijwilligers/add")
     */
    public function vrijwilligers_add($id)
    {
        $entity = $this->dao->find($id);

        $form = $this->createFormBuilder($entity)
            ->add('vrijwilligers', null, [
                'mapped' => false,
                'query_builder' => function (EntityRepository $repository) use ($entity) {
                    return $repository->createQueryBuilder('vrijwilliger')
                        ->leftJoin('vrijwilliger.klussen', 'klus', 'WITH', 'klus = :klus')
                        ->where('klus.id IS NULL')
                        ->setParameter('klus', $entity)
                    ;
                },
            ])
            ->add('submit', SubmitType::class)
            ->getForm()
        ;
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                foreach ($form->get('vrijwilligers')->getData() as $vrijwilliger) {
                    $entity->addVrijwilliger($vrijwilliger);
                }
                $this->dao->update($entity);
                $this->addFlash('success', 'Vrijwilliger(s) is/zijn toegevoegd.');
            } catch (\Exception $e) {
                var_dump($e); die;
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }

            return $this->redirectToView($entity);
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{klusId}/vrijwilligers/{vrijwilligerId}/delete")
     */
    public function vrijwilligers_delete($klusId, $vrijwilligerId)
    {
        $entity = $this->dao->find($klusId);
        $vrijwilliger = $this->vrijwilligerDao->find($vrijwilligerId);

        $entity->getVrijwilligers()->removeElement($vrijwilliger);
        $this->dao->update($entity);

        return $this->redirectToView($entity);
    }

    private function getFilter()
    {
        return $this->createForm(KlusFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
    }

    private function redirectToIndex()
    {
        return $this->redirectToRoute('hs_klussen_index');
    }

    private function redirectToView(Klus $entity)
    {
        return $this->redirectToRoute('hs_klussen_view', ['id' => $entity->getId()]);
    }
}
