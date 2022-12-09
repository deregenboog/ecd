<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\ConfirmationType;
use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use TwBundle\Entity\Huuraanbod;
use TwBundle\Entity\Huurovereenkomst;
use TwBundle\Entity\Huurverzoek;
use TwBundle\Exception\TwException;
use TwBundle\Form\HuurovereenkomstCloseType;
use TwBundle\Form\HuurovereenkomstFilterType;
use TwBundle\Form\HuurovereenkomstType;
use TwBundle\Service\HuurovereenkomstDao;
use Symfony\Component\Routing\Annotation\Route;
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
    protected $baseRouteName = 'tw_huurovereenkomsten_';

    /**
     * @var HuurovereenkomstDao
     */
    protected $dao;

    /**
     * @var ExportInterface
     */
    protected $export;

    /**
     * @param HuurovereenkomstDao $dao
     * @param ExportInterface $export
     */
    public function __construct(HuurovereenkomstDao $dao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->export = $export;
    }


    public function dafterFind($entity)
    {
        $verslagen = $entity->getDocumenten();
        foreach($verslagen as $v)
        {
            $title = $v->getId();

        }
        $fv = $entity->getFinancieleverslagen();
        foreach($fv as $v)
        {
            $id = $v->getId();
        }
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
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
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
            } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $message =  $e->getMessage();
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
                } catch(UserException $e) {
//                $this->logger->error($e->getMessage(), ['exception' => $e]);
                    $message =  $e->getMessage();
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
