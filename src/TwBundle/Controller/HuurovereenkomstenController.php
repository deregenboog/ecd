<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\ConfirmationType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Entity\Huuraanbod;
use TwBundle\Entity\Huurovereenkomst;
use TwBundle\Entity\Huurverzoek;
use TwBundle\Exception\TwException;
use TwBundle\Form\HuurovereenkomstCloseType;
use TwBundle\Form\HuurovereenkomstFilterType;
use TwBundle\Form\HuurovereenkomstType;
use TwBundle\Service\HuurovereenkomstDaoInterface;

/**
 * @Route("/huurovereenkomsten")
 *
 * @Template
 */
class HuurovereenkomstenController extends AbstractController
{
    protected $title = 'Koppelingen';
    protected $entityName = 'koppeling';
    protected $entityClass = Huurovereenkomst::class;
    protected $formClass = HuurovereenkomstType::class;
    protected $filterFormClass = HuurovereenkomstFilterType::class;
    protected $baseRouteName = 'tw_huurovereenkomsten_';

    /**
     * @var HuurovereenkomstDaoInterface
     */
    protected $dao;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct(HuurovereenkomstDaoInterface $dao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->export = $export;
    }

    /**
     * @Route("/add")
     */
    public function addAction(Request $request)
    {
        $entityManager = $this->getEntityManager();
        $entity = $this->findEntity($entityManager);

        $huurovereenkomst = new Huurovereenkomst();
        $huurovereenkomst->setMedewerker($this->getMedewerker());
        if ($entity instanceof Huurverzoek) {
            $huurovereenkomst->setHuurverzoek($entity);
        } elseif ($entity instanceof Huuraanbod) {
            $huurovereenkomst->setHuuraanbod($entity);
        }

        $form = $this->getForm(HuurovereenkomstType::class, $huurovereenkomst);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $routeBase = $this->resolveRouteBase($entity);
            try {
                $entityManager->persist($huurovereenkomst);
                $entityManager->flush();

                $this->addFlash('success', 'Huurovereenkomst is opgeslagen.');
            } catch (UserException $e) {
                //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
                //                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);

                return $this->redirectToRoute($routeBase.'_index');
            }

            return $this->redirectToRoute('tw_huurovereenkomsten_view', ['id' => $huurovereenkomst->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/close")
     */
    public function close($id)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getFilters()->enable('active');
        $huurovereenkomst = $entityManager->find(Huurovereenkomst::class, $id);

        $form = $this->getForm(HuurovereenkomstCloseType::class, $huurovereenkomst);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
            } catch (UserException $e) {
                //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message = $e->getMessage();
                $this->addFlash('danger', $message);
                //                return $this->redirectToRoute('app_klanten_index');
            } catch (\Exception $e) {
                $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('tw_huurovereenkomsten_view', ['id' => $huurovereenkomst->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/reopen")
     */
    public function reopen($id)
    {
        $entityManager = $this->getEntityManager();
        $huurovereenkomst = $entityManager->find(Huurovereenkomst::class, $id);

        $form = $this->getForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $huurovereenkomst->reopen();
                    $entityManager->flush();

                    $this->addFlash('success', 'Koppeling is heropend.');
                } catch (UserException $e) {
                    //                $this->logger->error($e->getMessage(), ['exception' => $e]);
                    $message = $e->getMessage();
                    $this->addFlash('danger', $message);
                    //                return $this->redirectToRoute('app_klanten_index');
                } catch (\Exception $e) {
                    $message = $this->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                    $this->addFlash('danger', $message);
                }
            }

            return $this->redirectToRoute('tw_huurovereenkomsten_view', ['id' => $huurovereenkomst->getId()]);
        }

        return [
            'huurovereenkomst' => $huurovereenkomst,
            'form' => $form->createView(),
        ];
    }

    protected function getDownloadFilename()
    {
        return sprintf('tijdelijk-wonen-koppelingen-%s.xlsx', (new \DateTime())->format('d-m-Y'));
    }

    private function findEntity(EntityManagerInterface $entityManager)
    {
        switch (true) {
            case $this->getRequest()->query->has('huurverzoek'):
                $class = Huurverzoek::class;
                $id = $this->getRequest()->query->get('huurverzoek');
                break;
            case $this->getRequest()->query->has('huuraanbod'):
                $class = Huuraanbod::class;
                $id = $this->getRequest()->query->get('huuraanbod');
                break;
            default:
                throw new TwException('Kan geen koppeling voor deze entiteit maken');
        }

        return $entityManager->find($class, $id);
    }

    private function resolveRouteBase($entity)
    {
        switch (true) {
            case $entity instanceof Huurverzoek:
                $routeBase = 'tw_huurverzoeken';
                break;
            case $entity instanceof Huuraanbod:
                $routeBase = 'tw_huuraanbiedingen';
                break;
            default:
                throw new TwException('Kan geen verslag aan deze entiteit toevoegen');
        }

        return $routeBase;
    }
}
