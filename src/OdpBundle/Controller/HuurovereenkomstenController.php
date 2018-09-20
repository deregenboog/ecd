<?php

namespace OdpBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\ConfirmationType;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use OdpBundle\Entity\Huuraanbod;
use OdpBundle\Entity\Huurovereenkomst;
use OdpBundle\Entity\Huurverzoek;
use OdpBundle\Exception\OdpException;
use OdpBundle\Form\HuurovereenkomstCloseType;
use OdpBundle\Form\HuurovereenkomstFilterType;
use OdpBundle\Form\HuurovereenkomstType;
use OdpBundle\Service\HuurovereenkomstDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/huurovereenkomsten")
 * @Template
 */
class HuurovereenkomstenController extends AbstractController
{
    protected $title = 'Koppelingen';
    protected $entityName = 'koppeling';
    protected $entityClass = Huurovereenkomst::class;
    protected $formClass = HuurovereenkomstType::class;
    protected $filterFormClass = HuurovereenkomstFilterType::class;
    protected $baseRouteName = 'odp_huurovereenkomsten_';

    /**
     * @var HuurovereenkomstDao
     *
     * @DI\Inject("odp.dao.huurovereenkomst")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("odp.export.koppelingen")
     */
    protected $export;

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

        $form = $this->createForm(HuurovereenkomstType::class, $huurovereenkomst);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $routeBase = $this->resolveRouteBase($entity);
            try {
                $entityManager->persist($huurovereenkomst);
                $entityManager->flush();

                $this->addFlash('success', 'Huurovereenkomst is opgeslagen.');
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);

                return $this->redirectToRoute($routeBase.'_index');
            }

            return $this->redirectToRoute('odp_huurovereenkomsten_view', ['id' => $huurovereenkomst->getId()]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{id}/close")
     */
    public function close($id)
    {
        $entityManager = $this->getEntityManager();
        $huurovereenkomst = $entityManager->find(Huurovereenkomst::class, $id);

        $form = $this->createForm(HuurovereenkomstCloseType::class, $huurovereenkomst);
        $form->handleRequest($this->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->flush();
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToRoute('odp_huurovereenkomsten_view', ['id' => $huurovereenkomst->getId()]);
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

        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('yes')->isClicked()) {
                try {
                    $huurovereenkomst->reopen();
                    $entityManager->flush();

                    $this->addFlash('success', 'Koppeling is heropend.');
                } catch (\Exception $e) {
                    $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                    $this->addFlash('danger', $message);
                }
            }

            return $this->redirectToRoute('odp_huurovereenkomsten_view', ['id' => $huurovereenkomst->getId()]);
        }

        return [
            'huurovereenkomst' => $huurovereenkomst,
            'form' => $form->createView(),
        ];
    }

    protected function getDownloadFilename()
    {
        return sprintf('onder-de-pannen-koppelingen-%s.xlsx', (new \DateTime())->format('d-m-Y'));
    }

    private function findEntity(EntityManager $entityManager)
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
                throw new OdpException('Kan geen koppeling voor deze entiteit maken');
        }

        return $entityManager->find($class, $id);
    }

    private function resolveRouteBase($entity)
    {
        switch (true) {
            case $entity instanceof Huurverzoek:
                $routeBase = 'odp_huurverzoeken';
                break;
            case $entity instanceof Huuraanbod:
                $routeBase = 'odp_huuraanbiedingen';
                break;
            default:
                throw new OdpException('Kan geen verslag aan deze entiteit toevoegen');
        }

        return $routeBase;
    }
}
