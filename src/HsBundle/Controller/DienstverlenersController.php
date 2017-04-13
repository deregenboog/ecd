<?php

namespace HsBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Entity\Klant;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use AppBundle\Service\KlantDaoInterface;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Form\DienstverlenerFilterType;
use HsBundle\Form\DienstverlenerType;
use HsBundle\Service\DienstverlenerDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hs/dienstverleners")
 */
class DienstverlenersController extends SymfonyController
{
    use MemosAddControllerTrait, DocumentenAddControllerTrait;

    /**
     * @var DienstverlenerDaoInterface
     *
     * @DI\Inject("hs.dao.dienstverlener")
     */
    private $dao;

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("app.dao.klant")
     */
    private $klantDao;

    private $enabledFilters = [
        'rijbewijs',
        'klant' => ['id', 'naam', 'stadsdeel'],
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

        $response = $this->render('@Hs/vrijwilligers/download.csv.twig', ['collection' => $collection]);

        $filename = sprintf('homeservice-vrijwilligers-%s.xls', (new \DateTime())->format('d-m-Y'));
        $response->headers->set('Content-type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s";', $filename));
        $response->headers->set('Content-Transfer-Encoding', 'binary');

        return $response;
    }

    /**
     * @Route("/add")
     */
    public function add(Request $request)
    {
        if ($klantId = $request->get('klantId')) {
            if ($klantId === 'new') {
                $klant = new Klant();
            } else {
                $klant = $this->klantDao->find($klantId);
            }

            // redirect if already exists
            $dienstverlener = $this->dao->findOneByKlant($klant);
            if ($dienstverlener) {
                return $this->redirectToView($dienstverlener);
            }

            $dienstverlener = new Dienstverlener($klant);
            $creationForm = $this->getForm($dienstverlener)->handleRequest($this->getRequest());

            if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                try {
                    $this->dao->create($dienstverlener);
                    $this->addFlash('success', 'Dienstverlener is opgeslagen.');

                    return $this->redirectToRoute('hs_dienstverleners_memos_add', ['id' => $dienstverlener->getId()]);
                } catch (\Exception $e) {
                    $this->addFlash('danger', 'Er is een fout opgetreden.');
                }

                return $this->redirectToIndex();
            }

            return [
                'creationForm' => $creationForm->createView(),
            ];
        }

        $filterForm = $this->createForm(AppKlantFilterType::class, null, [
            'enabled_filters' => ['naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($this->getRequest());

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $builder = $this->getEntityManager()->getRepository(Klant::class)
                ->createQueryBuilder('klant')
                ->where('klant.disabled = false')
                ->orderBy('klant.achternaam')
            ;
            $filterForm->getData()->applyTo($builder);

            return [
                'klanten' => $builder->getQuery()->getResult(),
            ];
        }

        return [
            'filterForm' => $filterForm->createView(),
        ];
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
     * @Route("/{id}/edit")
     */
    public function edit(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->getForm($entity)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);
                $this->addFlash('success', 'Dienstverlener is opgeslagen.');

                return $this->redirectToView($entity);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Er is een fout opgetreden.');
            }
        }

        return [
            'entity' => $entity,
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
                $this->dao->delete($entity);
                $this->addFlash('success', 'Dienstverlener is verwijderd.');
            }

            return $this->redirectToIndex();
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    private function getFilter()
    {
        return $this->createForm(DienstverlenerFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
    }

    private function getForm(Dienstverlener $data = null)
    {
        return $this->createForm(DienstverlenerType::class, $data);
    }

    private function redirectToIndex()
    {
        return $this->redirectToRoute('hs_dienstverleners_index');
    }

    private function redirectToView(Dienstverlener $entity)
    {
        return $this->redirectToRoute('hs_dienstverleners_view', ['id' => $entity->getId()]);
    }
}
