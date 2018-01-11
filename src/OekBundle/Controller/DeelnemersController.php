<?php

namespace OekBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Entity\Klant;
use OekBundle\Entity\Deelnemer;
use OekBundle\Form\DeelnemerType;
use AppBundle\Form\KlantFilterType;
use Doctrine\DBAL\Driver\PDOException;
use OekBundle\Form\DeelnemerFilterType;
use AppBundle\Form\ConfirmationType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;
use OekBundle\Entity\Aanmelding;
use OekBundle\Form\AanmeldingType;
use OekBundle\Form\AfsluitingType;
use OekBundle\Entity\Afsluiting;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use OekBundle\Service\DeelnemerDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use AppBundle\Controller\AbstractController;

/**
 * @Route("/deelnemers")
 */
class DeelnemersController extends AbstractController
{
    protected $title = 'Deelnemers';
    protected $entityName = 'deelnemer';
    protected $entityClass = Deelnemer::class;
    protected $formClass = DeelnemerType::class;
    protected $filterFormClass = DeelnemerFilterType::class;
    protected $baseRouteName = 'oek_deelnemers_';

    /**
     * @var DeelnemerDaoInterface
     *
     * @DI\Inject("OekBundle\Service\DeelnemerDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("oek.export.deelnemer")
     */
    protected $export;

    /**
     * @var \AppBundle\Service\KlantDaoInterface
     *
     * @DI\Inject("app.dao.klant")
     */
    private $klantDao;

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
     * @Route("/{id}/open")
     */
    public function openAction($id)
    {
        $deelnemer = $this->dao->find($id);

        $aanmelding = new Aanmelding();
        $deelnemer->addAanmelding($aanmelding);

        $form = $this->createForm(AanmeldingType::class, $aanmelding);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($deelnemer);
                $this->addFlash('success', 'Het dossier is heropend.');

                return $this->redirectToRoute('oek_deelnemers_view', ['id' => $deelnemer->getId()]);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return [
            'form' => $form->createView(),
            'deelnemer' => $deelnemer,
        ];
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $deelnemer = $this->dao->find($id);

        $afsluiting = new Afsluiting();
        $deelnemer->addAfsluiting($afsluiting);

        $form = $this->createForm(AfsluitingType::class, $afsluiting);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->dao->update($deelnemer);
                $this->addFlash('success', 'Het dossier is afgesloten.');

                return $this->redirectToRoute('oek_deelnemers_view', ['id' => $deelnemer->getId()]);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return [
            'form' => $form->createView(),
            'deelnemer' => $deelnemer,
        ];
    }

    protected function getDownloadFilename()
    {
        return sprintf('op-eigen-kracht-deelnemers-%s.xls', (new \DateTime())->format('d-m-Y'));
    }

    private function doSearch(Request $request)
    {
        $filterForm = $this->createForm(KlantFilterType::class, null, [
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

    private function doAdd(Request $request)
    {
        $klantId = $request->get('klant');
        if ('new' === $klantId) {
            $klant = new Klant();
        } else {
            $klant = $this->klantDao->find($klantId);
        }

        // redirect if already exists
        $deelnemer = $this->dao->findOneByKlant($klant);
        if ($deelnemer) {
            return $this->redirectToView($deelnemer);
        }

        $deelnemer = new Klant($klant);
        $creationForm = $this->createForm(DeelnemerType::class, $deelnemer);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($deelnemer);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                return $this->redirectToRoute('oek_deelnemers_view', ['id' => $deelnemer->getId()]);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'creationForm' => $creationForm->createView(),
        ];
    }
}
