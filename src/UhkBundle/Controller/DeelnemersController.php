<?php

namespace UhkBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\KlantFilterType;
use AppBundle\Service\KlantDaoInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use UhkBundle\Entity\Deelnemer;
use UhkBundle\Form\DeelnemerFilterType;
use UhkBundle\Form\DeelnemerType;
use UhkBundle\Security\Permissions;
use UhkBundle\Service\DeelnemerDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UhkBundle\Service\VerslagDao;

/**
 * @Route("/deelnemers")
 * @Template
 */
class DeelnemersController extends AbstractController
{
    protected $title = 'Deelnemers';
    protected $entityName = 'deelnemer';
    protected $entityClass = Deelnemer::class;
    protected $formClass = DeelnemerType::class;
    protected $filterFormClass = DeelnemerFilterType::class;
    protected $baseRouteName = 'uhk_deelnemers_';
//    protected $disabledActions = ['delete'];

    /**
     * @var DeelnemerDaoInterface
     */
    protected $dao;

    /**
     * @var KlantDaoInterface
     */
    protected $klantDao;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("UhkBundle\Service\DeelnemerDao");
        $this->klantDao = $container->get("AppBundle\Service\KlantDao");
    
        return $previous;
    }

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
     * @Template
     */
    public function indexAction(Request $request)
    {
        $form = $this->getForm($this->filterFormClass);
        $form->handleRequest($request);
        $filter = $form->getData();

        $page = $request->get('page', 1);
        if ($this->isGranted('ROLE_UHK_BEHEER')) {
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
     * @Template
     */
    public function viewAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

//        $this->denyAccessUnlessGranted(Permissions::ACCESS, $entity);

        return parent::viewAction($request, $id);
    }

    /**
     * @Route("/{id}/edit")
     * @Template
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

//        $this->denyAccessUnlessGranted(Permissions::ACCESS, $entity);

        return parent::editAction($request, $id);
    }

    /**
     * @Route("/{id}/delete")
     * @Template
     */
    public function deleteAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
//        $this->denyAccessUnlessGanted(Permissions::ACCESS, $entity);

        return parent::deleteAction($request, $id);
    }

    /**
     * @Route("/{id}/deleteVerslag/{verslagId}")
     * @param Request $request
     * @Template
     */
    public function deleteVerslagAction(Request $request, $id, $verslagId)
    {
        $entity = $this->dao->find($id);

        if ($entity->getMedewerker() != $this->getMedewerker() && !$this->isGranted("ROLE_UHK_BEHEER")) {
            throw new AccessDeniedException("Mag alleen verslagen verwijderen als je een beheerder bent of het over een eigen verslag gaat.");
        }
        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $url = $request->get('redirect');
                $viewUrl = $this->generateUrl($this->baseRouteName . 'view', ['id' => $entity->getId(),'_fragment'=>'verslagen']);

                $verslagDao = new VerslagDao($this->getEntityManager(),$this->getPaginator(),5);
                $verslag = $verslagDao->find($verslagId);
                $verslagDao->delete($verslag);

                $this->addFlash('success',   'Verslag is verwijderd.');


                return $this->redirect($viewUrl);

            } else {
                if (isset($url)) {
                    return $this->redirect($url);
                }

                return $this->redirectToView($entity);
            }
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    private function doSearch(Request $request)
    {
        $filterForm = $this->getForm(KlantFilterType::class, null, [
            'enabled_filters' => ['id', 'naam', 'bsn', 'geboortedatum'],
        ]);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $count = (int)$this->klantDao->countAll($filterForm->getData());
            if (0 === $count) {
                $this->addFlash('info', sprintf('De zoekopdracht leverde geen resultaten op. Maak een nieuwe %s aan.', $this->entityName));

                return $this->redirectToRoute($this->baseRouteName . 'add', ['klant' => 'new']);
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

        $deelnemer = new Deelnemer($klant, $this->getMedewerker());
        $creationForm = $this->getForm(DeelnemerType::class, $deelnemer);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($deelnemer);
                $this->addFlash('success', ucfirst($this->entityName) . ' is opgeslagen.');

                return $this->redirectToView($deelnemer);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
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
