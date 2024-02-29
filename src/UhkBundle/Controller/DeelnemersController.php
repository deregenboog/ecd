<?php

namespace UhkBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Exception\UserException;
use AppBundle\Form\ConfirmationType;
use AppBundle\Form\KlantFilterType;
use AppBundle\Service\KlantDaoInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
use UhkBundle\Service\VerslagDaoInterface;

/**
 * @Route("/deelnemers")
 * @Template
 */
class DeelnemersController extends AbstractController
{
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

    /**
     * @var VerslagDaoInterface
     */
    protected $verslagDao;

    public function __construct(DeelnemerDaoInterface $dao, KlantDaoInterface $klantDao, VerslagDaoInterface $verslagDao)
    {
        $this->dao = $dao;
        $this->klantDao = $klantDao;
        $this->verslagDao = $verslagDao;
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
     * @Route("/{id}/open")
     * @Template
     */
    public function openAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
//        $this->denyAccessUnlessGanted(Permissions::ACCESS, $entity);
            $entity->setActief(true);

        $this->dao->update($entity);
        $this->addFlash("info","Deelnemer weer actief gemaakt.");
       return $this->redirectToView($entity);
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
                $viewUrl = $this->generateUrl($this->baseRouteName . 'view', ['id' => $entity->getId(),'_fragment'=>'verslagen']);

                $verslag = $this->verslagDao->find($verslagId);
                $this->verslagDao->delete($verslag);

                $this->addFlash('success',   'Verslag is verwijderd.');
                return $this->redirect($viewUrl);

            } else {
                if ($url = $request->get('redirect')) {
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

    protected function doSearch(Request $request)
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
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
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
