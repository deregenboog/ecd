<?php

namespace ErOpUitBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant as AppKlant;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use ErOpUitBundle\Entity\Klant;
use ErOpUitBundle\Form\KlantCloseType;
use ErOpUitBundle\Form\KlantFilterType;
use ErOpUitBundle\Form\KlantReopenType;
use ErOpUitBundle\Form\KlantType;
use ErOpUitBundle\Service\KlantDao;
use ErOpUitBundle\Service\KlantDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/klanten")
 * @Template
 */
class KlantenController extends AbstractController
{
    protected $title = 'Deelnemers';
    protected $entityName = 'deelnemer';
    protected $entityClass = Klant::class;
    protected $formClass = KlantType::class;
    protected $filterFormClass = KlantFilterType::class;
    protected $baseRouteName = 'eropuit_klanten_';

    /**
     * @var KlantDao
     */
    protected $dao;

    /**
     * @var \AppBundle\Service\KlantDao
     */
    private $klantDao;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @param KlantDao $dao
     * @param \AppBundle\Service\KlantDao $klantDao
     * @param ExportInterface $export
     */
    public function __construct(KlantDao $dao, \AppBundle\Service\KlantDao $klantDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->klantDao = $klantDao;
        $this->export = $export;
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
     * @Route("/{id}/reopen")
     */
    public function reopenAction(Request $request, $id)
    {
        $this->formClass = KlantReopenType::class;

        $entity = $this->dao->find($id);
        $entity->reopen();

        return $this->processForm($request, $entity);
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $this->formClass = KlantCloseType::class;

        $entity = $this->dao->find($id);
        $entity->close();

        return $this->processForm($request, $entity);
    }

    protected function doSearch(Request $request)
    {
        $filterForm = $this->getForm(AppKlantFilterType::class, null, [
            'enabled_filters' => ['naam', 'bsn', 'geboortedatum'],
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

                return [
                    'filterForm' => $filterForm->createView(),
                ];
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
            $appKlant = new AppKlant();
        } else {
            $appKlant = $this->klantDao->find($klantId);
        }

        // redirect if already exists
        $klant = $this->dao->findOneByKlant($appKlant);
        if ($klant) {
            return $this->redirectToView($klant);
        }

        $klant = new Klant($appKlant);
        $creationForm = $this->getForm(KlantType::class, $klant);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($klant);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                return $this->redirectToView($klant);
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
            'creationForm' => $creationForm->createView(),
        ];
    }
}
