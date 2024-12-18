<?php

namespace ScipBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Exception\UserException;
use AppBundle\Form\KlantFilterType;
use AppBundle\Service\KlantDaoInterface;
use ScipBundle\Entity\Deelnemer;
use ScipBundle\Form\DeelnemerFilterType;
use ScipBundle\Form\DeelnemerType;
use ScipBundle\Security\Permissions;
use ScipBundle\Service\DeelnemerDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/deelnemers")
 *
 * @Template
 */
class DeelnemersController extends AbstractController
{
    protected $title = 'Vrijwilligers';
    protected $entityName = 'vrijwilliger';
    protected $entityClass = Deelnemer::class;
    protected $formClass = DeelnemerType::class;
    protected $filterFormClass = DeelnemerFilterType::class;
    protected $baseRouteName = 'scip_deelnemers_';
    protected $disabledActions = ['delete'];

    /**
     * @var DeelnemerDaoInterface
     */
    protected $dao;

    /**
     * @var KlantDaoInterface
     */
    protected $klantDao;

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        if ($request->get('klant')) {
            return $this->doAdd($request);
        }

        return $this->doSearch($request);
    }

    /**
     * @Route("/")
     *
     * @Template
     */
    public function indexAction(Request $request)
    {
        $form = $this->getForm($this->filterFormClass);
        $form->handleRequest($request);
        $filter = $form->getData();

        $page = $request->get('page', 1);
        if ($this->isGranted('ROLE_SCIP_BEHEER')) {
            $pagination = $this->dao->findAll($page, $filter);
        } else {
            $pagination = $this->dao->findByMedewerker($this->getMedewerker(), $page, $filter);
        }

        return [
            'filter' => isset($form) ? $form->createView() : null,
            'pagination' => $pagination,
        ];
    }

    /**
     * @Route("/{id}/view")
     *
     * @Template
     */
    public function viewAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $this->denyAccessUnlessGranted(Permissions::ACCESS, $entity);

        return parent::viewAction($request, $id);
    }

    /**
     * @Route("/{id}/edit")
     *
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $this->denyAccessUnlessGranted(Permissions::ACCESS, $entity);

        return parent::editAction($request, $id);
    }

    /**
     * @Route("/{id}/delete")
     *
     * @Template
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $this->denyAccessUnlessGranted(Permissions::ACCESS, $entity);

        return parent::deleteAction($request, $id);
    }

    protected function doSearch(Request $request)
    {
        $filterForm = $this->getForm(KlantFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $count = (int) $this->klantDao->countAll($filterForm->getData());
            if (0 === $count) {
                $this->addFlash('info', sprintf('De zoekopdracht leverde geen resultaten op. Maak een nieuwe %s aan.', $this->entityName));

                return $this->redirectToRoute($this->baseRouteName.'add', ['klant' => 'new']);
            }

            if ($count > 100) {
                $filterForm->addError(new FormError('De zoekopdracht leverde teveel resultaten op. Probeer het opnieuw met een specifiekere zoekopdracht.'));
            }

            return [
                'filterForm' => $filterForm->createView(),
                'klanten' => $this->klantDao->findAll(null, $filterForm->getData()),
            ];
        }

        return [
            'filterForm' => $filterForm->createView(),
        ];
    }

    protected function doAdd(Request $request)
    {
        $klantId = $request->get('klant');
        if ('new' === $klantId) {
            $klant = new Klant();
        } else {
            $klant = $this->klantDao->find($klantId);
            if ($klant) {
                // redirect if already exists
                $deelnemer = $this->dao->findOneByKlant($klant);
                if ($deelnemer) {
                    return $this->redirectToView($deelnemer);
                }
            }
        }

        $deelnemer = new Deelnemer($klant);
        $creationForm = $this->getForm(DeelnemerType::class, $deelnemer);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($deelnemer);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                $url = $this->generateUrl('scip_deelnames_add', [
                    'deelnemer' => $deelnemer->getId(),
                    'redirect' => $this->generateUrl('scip_deelnemers_view', ['id' => $deelnemer->getId()]),
                ]);

                return $this->redirect($url);
            } catch (UserException $e) {
                //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
                //                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'entity' => $deelnemer,
            'creationForm' => $creationForm->createView(),
        ];
    }
}
