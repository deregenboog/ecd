<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Exception\UserException;
use AppBundle\Export\GenericExport;
use AppBundle\Form\KlantFilterType;
use AppBundle\Service\KlantDao;
use DagbestedingBundle\Entity\Deelnemer;
use DagbestedingBundle\Form\DeelnemerCloseType;
use DagbestedingBundle\Form\DeelnemerFilterType;
use DagbestedingBundle\Form\DeelnemerReopenType;
use DagbestedingBundle\Form\DeelnemerSelectType;
use DagbestedingBundle\Form\DeelnemerType;
use DagbestedingBundle\Service\DeelnemerDao;
use DagbestedingBundle\Service\DeelnemerDaoInterface;
use IzBundle\Service\KlantDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/deelnemers")
 * @Template
 */
class DeelnemersController extends AbstractController
{
    protected $title = 'Deelnemers';
    protected $entityName = 'Deelnemer';
    protected $entityClass = Deelnemer::class;
    protected $formClass = DeelnemerType::class;
    protected $filterFormClass = DeelnemerFilterType::class;
    protected $baseRouteName = 'dagbesteding_deelnemers_';

    /**
     * @var DeelnemerDao
     *
     * @DI\Inject("DagbestedingBundle\Service\DeelnemerDao")
     */
    protected $dao;

    /**
     * @var GenericExport
     *
     * @DI\Inject("dagbesteding.export.deelnemers")
     */
    protected $export;

    /**
     * @var KlantDao
     */
    private $klantDao;

    /**
     * @param DeelnemerDao $dao
     * @param GenericExport $export
     * @param KlantDaoInterface $klantDao
     */
    public function __construct(DeelnemerDao $dao, KlantDao $klantDao, GenericExport $export)
    {
        $this->dao = $dao;
        $this->export = $export;
        $this->klantDao = $klantDao;
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
                'deelnemers' => $this->klantDao->findAll(null, $filterForm->getData()),
            ];
        }

        return [
            'filterForm' => $filterForm->createView(),
        ];
    }


    public function doAdd(Request $request)
    {
        if ($request->query->has('klant')) {
            $klant = new Klant();
            if ('new' !== $request->query->get('klant')) {
                $klant = $this->getEntityManager()->find(Klant::class, $request->query->get('klant'));
                if ($klant) {
                    // redirect if already exists
                    $deelnemer = $this->dao->findOneByKlant($klant);
                    if ($deelnemer) {
                        return $this->redirectToView($deelnemer);
                    }
                }
            }

            $entity = new Deelnemer();
            $entity->setKlant($klant);
            $this->getEntityManager()->getFilters()->enable('active');
            $creationForm = $this->getForm(DeelnemerType::class, $entity);
            $creationForm->handleRequest($request);

            if ($creationForm->isSubmitted() && $creationForm->isValid()) {
                try {
                    $this->dao->create($entity);

                    $this->addFlash('success', $this->entityName.' is opgeslagen.');

                    return $this->redirectToView($entity);
                }  catch(UserException $e) {
//                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                    $message =  $e->getMessage();
                    $this->addFlash('danger', $message);
                    $this->redirectToIndex();
                }  catch (\Exception $e) {
                    $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                    $this->addFlash('danger', $message);

                    return $this->redirectToIndex();
                }
            }

            return [
                'creationForm' => $creationForm->createView(),
                'klant'=>$klant,
            ];
        }


    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        $this->getEntityManager()->getFilters()->enable('active');
        $form = $this->getForm(DeelnemerCloseType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($entity);

                $this->addFlash('success', $this->entityName.' is afgesloten.');
            } catch(UserException $e) {
//                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            }  catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToView($entity);
        }

        return [
            'deelnemer' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/{id}/reopen")
     */
    public function reopenAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        $form = $this->getForm(DeelnemerReopenType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entity->reopen();
                $this->dao->update($entity);

                $this->addFlash('success', $this->entityName.' is heropend.');
            } catch(UserException $e) {
//                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
                $this->addFlash('danger', $message);
//                return $this->redirectToRoute('app_klanten_index');
            }  catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToView($entity);
        }

        return [
            'deelnemer' => $entity,
            'form' => $form->createView(),
        ];
    }
}
