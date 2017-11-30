<?php

namespace OekBundle\Controller;

use AppBundle\Controller\SymfonyController;
use AppBundle\Entity\Klant;
use OekBundle\Entity\OekKlant;
use OekBundle\Form\OekKlantType;
use AppBundle\Form\KlantFilterType;
use OekBundle\Form\OekKlantSelectType;
use Doctrine\DBAL\Driver\PDOException;
use OekBundle\Form\OekKlantFilterType;
use AppBundle\Form\ConfirmationType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;
use OekBundle\Entity\OekAanmelding;
use OekBundle\Form\OekAanmeldingType;
use OekBundle\Form\OekAfsluitingType;
use OekBundle\Entity\OekAfsluiting;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/klanten")
 */
class KlantenController extends SymfonyController
{
    private $enabledFilters = [
        'klant' => ['id', 'naam', 'stadsdeel'],
        'training',
        'aanmelddatum',
        'afsluitdatum',
    ];

    private $sortFieldWhitelist = [
        'klant.id',
        'klant.achternaam',
        'klant.werkgebied',
        'oekTraining.naam',
        'oekAanmelding.datum',
        'oekAfsluiting.datum',
    ];

    /**
     * @Route("/")
     */
    public function index()
    {
        $repository = $this->getEntityManager()->getRepository(OekKlant::class);
        $builder = $repository->createQueryBuilder('oekKlant')
            ->select('oekKlant, klant, oekAanmelding, oekAfsluiting, verwijzingAanmelding, verwijzingAfsluiting, oekDossierStatus, oekDeelname, oekTraining')
            ->innerJoin('oekKlant.klant', 'klant')
            ->leftJoin('oekKlant.oekAanmelding', 'oekAanmelding')
            ->leftJoin('oekKlant.oekAfsluiting', 'oekAfsluiting')
            ->leftJoin('oekAanmelding.verwijzing', 'verwijzingAanmelding')
            ->leftJoin('oekAfsluiting.verwijzing', 'verwijzingAfsluiting')
            ->leftJoin('oekKlant.oekDossierStatus', 'oekDossierStatus')
            ->leftJoin('oekKlant.oekDeelnames', 'oekDeelname')
            ->leftJoin('oekDeelname.oekTraining', 'oekTraining')
            ->andWhere('klant.disabled = false')
        ;

        $filter = $this->createFilter();
        if ($filter->isValid()) {
            $filter->getData()->applyTo($builder);
            if ($filter->get('download')->isClicked()) {
                return $this->download($builder);
            }
        }

        $pagination = $this->getPaginator()->paginate($builder, $this->getRequest()->get('page', 1), 20, [
            'defaultSortFieldName' => 'klant.achternaam',
            'defaultSortDirection' => 'asc',
            'sortFieldWhitelist' => $this->sortFieldWhitelist,
            'wrap-queries' => true, // because of HAVING clause in filter
        ]);

        return ['filter' => $filter->createView(), 'pagination' => $pagination];
    }

    public function download(QueryBuilder $builder)
    {
        $oekKlanten = $builder->getQuery()->getResult();

        $response = $this->render('@Oek/klanten/download.csv.twig', compact('oekKlanten'));

        $filename = sprintf('op-eigen-kracht-deelnemers-%s.xls', (new \DateTime())->format('d-m-Y'));
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
        $entityManager = $this->getEntityManager();
        $oekKlant = $entityManager->find(OekKlant::class, $id);

        return compact('oekKlant');
    }

    /**
     * @Route("/add")
     */
    public function add()
    {
        $klantId = $this->getRequest()->query->get('klantId');
        $entityManager = $this->getEntityManager();

        if ($klantId) {
            if ('new' === $klantId) {
                $klant = new Klant();
            } else {
                $klant = $entityManager->find(Klant::class, $klantId);
            }

            $oekKlant = new OekKlant();
            $oekKlant->setKlant($klant);

            $creationForm = $this->createForm(OekKlantType::class, $oekKlant);
            $creationForm->handleRequest($this->getRequest());

            if ($creationForm->isValid()) {
                try {
                    $entityManager->persist($oekKlant);
                    $entityManager->flush();

                    $this->addFlash('success', 'Klant is opgeslagen.');

                    return $this->redirectToRoute('oek_klanten_view', ['id' => $oekKlant->getId()]);
                } catch (\Exception $e) {
                    if ($e->getPrevious() instanceof PDOException && 23000 == $e->getPrevious()->getCode()) {
                        $this->addFlash('danger', 'Deze klant heeft al een Op-eigen-kracht-dossier.');
                    } else {
                        $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                        $this->addFlash('danger', $message);
                    }
                } finally {
                    return $this->redirectToRoute('oek_klanten_index');
                }
            }

            $this->set('creationForm', $creationForm->createView());

            return;
        }

        $filterForm = $this->createForm(KlantFilterType::class, null, [
            'enabled_filters' => ['naam'],
        ]);
        $filterForm->handleRequest($this->getRequest());

        $selectionForm = $this->createForm(OekKlantSelectType::class, null, [
            'filter' => $filterForm->getData(),
        ]);
        $selectionForm->handleRequest($this->getRequest());

        if ($filterForm->isValid()) {
            $this->set('selectionForm', $selectionForm->createView());

            return;
        }

        if ($selectionForm->isSubmitted() && $selectionForm->isValid()) {
            $oekKlant = $selectionForm->getData();
            if ($oekKlant->getKlant() instanceof Klant) {
                return $this->redirectToRoute('oek_klanten_add', ['klantId' => $oekKlant->getKlant()->getId()]);
            }

            return $this->redirectToRoute('oek_klanten_add', ['klantId' => 'new']);
        }

        return ['filterForm' => $filterForm->createView()];
    }

    /**
     * @Route("/{id}/edit")
     */
    public function edit($id)
    {
        $entityManager = $this->getEntityManager();
        $oekKlant = $entityManager->find(OekKlant::class, $id);

        $form = $this->createForm(OekKlantType::class, $oekKlant);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            try {
                $entityManager->flush();

                $this->addFlash('success', 'Klant is opgeslagen.');

                return $this->redirectToRoute('oek_klanten_view', ['id' => $oekKlant->getId()]);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return ['form' => $form->createView(), 'oekKlant' => $oekKlant];
    }

    /**
     * @Route("/{id}/open")
     */
    public function open($id)
    {
        $entityManager = $this->getEntityManager();
        $oekKlant = $entityManager->find(OekKlant::class, $id);

        $oekAanmelding = new OekAanmelding();
        $form = $this->createForm(OekAanmeldingType::class, $oekAanmelding);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            try {
                $oekKlant->addOekAanmelding($oekAanmelding);
                $entityManager->flush();

                $this->addFlash('success', 'Het dossier is heropend.');

                return $this->redirectToRoute('oek_klanten_view', ['id' => $oekKlant->getId()]);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return ['form' => $form->createView(), 'oekKlant' => $oekKlant];
    }

    /**
     * @Route("/{id}/close")
     */
    public function close($id)
    {
        $entityManager = $this->getEntityManager();
        $oekKlant = $entityManager->find(OekKlant::class, $id);

        $oekAfsluiting = new OekAfsluiting();
        $form = $this->createForm(OekAfsluitingType::class, $oekAfsluiting);
        $form->handleRequest($this->getRequest());

        if ($form->isValid()) {
            try {
                $oekKlant->addOekAfsluiting($oekAfsluiting);
                $entityManager->flush();

                $this->addFlash('success', 'Het dossier is afgesloten.');

                return $this->redirectToRoute('oek_klanten_view', ['id' => $oekKlant->getId()]);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }
        }

        return ['form' => $form->createView(), 'oekKlant' => $oekKlant];
    }

    /**
     * @Route("/{id}/delete")
     */
    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $oekKlant = $entityManager->find(OekKlant::class, $id);

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                $entityManager->remove($oekKlant);
                $entityManager->flush();

                $this->addFlash('success', 'Klant is verwijderd.');
            }

            return $this->redirectToRoute('oek_klanten_index');
        }

        return ['form' => $form->createView(), 'oekKlant' => $oekKlant];
    }

    /**
     * @return FormInterface
     */
    private function createFilter()
    {
        $filter = $this->createForm(OekKlantFilterType::class, null, [
            'enabled_filters' => $this->enabledFilters,
        ]);
        $filter->handleRequest($this->getRequest());

        return $filter;
    }
}
