<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\AmocLand;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Land;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\KlantFilterType as AppKlantFilterType;
use AppBundle\Service\KlantDaoInterface;
use InloopBundle\Form\KlantType;
use MwBundle\Entity\Document;
use MwBundle\Entity\Info;
use MwBundle\Form\InfoType;
use MwBundle\Form\KlantFilterType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/klanten")
 * @Template
 */
class KlantenController extends AbstractController
{
    protected $title = 'Klanten';
    protected $entityName = 'klant';
    protected $entityClass = Klant::class;
    protected $formClass = KlantType::class;
    protected $filterFormClass = KlantFilterType::class;
    protected $baseRouteName = 'mw_klanten_';

    /**
     * @var KlantDaoInterface
     */
    protected $dao;

    /**
     * @var \AppBundle\Service\KlantDaoInterface
     */
    protected $klantDao;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("MwBundle\Service\KlantDao");
        $this->klantDao = $container->get("AppBundle\Service\KlantDao");
        $this->export = $container->get("mw.export.klanten");
    
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
     * @Route("/{klant}/info")
     */
    public function infoEditAction(Request $request, Klant $klant)
    {
        $em = $this->getEntityManager();
        $entity = $em->getRepository(Info::class)->findOneBy(['klant' => $klant]);
        if (!$entity) {
            $entity = new Info($klant);
        }

        $form = $this->getForm(InfoType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if (!$entity->getId()) {
                    $em->persist($entity);
                }
                $em->flush();
                $this->addFlash('success', 'Info is opgeslagen.');
            } catch (\Exception $e) {
                $this->get('logger')->error($e->getMessage(), ['exception' => $e]);
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->afterFormSubmitted($request, $entity);
        }

        return [
            'form' => $form->createView(),
            'klant' => $klant,
        ];
    }

    /**
     * @Template
     */
    public function _documentenAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);
        $documenten = $this->getEntityManager()->getRepository(Document::class)
            ->findBy(['klant' => $klant], ['id' => 'DESC']);

        return [
            'klant' => $klant,
            'documenten' => $documenten,
        ];
    }

    /**
     * @Template
     */
    public function _mwAction(Request $request, $id)
    {
        $klant = $this->dao->find($id);
        $info = $this->getEntityManager()->getRepository(Info::class)->findOneBy(['klant' => $klant]);

        return [
            'klant' => $klant,
            'info' => $info,
        ];
    }

    protected function addParams($entity, Request $request)
    {
        assert($entity instanceof Klant);

        $event = new DienstenLookupEvent($entity->getId());
        if ($event->getKlantId()) {
            $this->get('event_dispatcher')->dispatch($event, Events::DIENSTEN_LOOKUP);
        }

        return [
            'diensten' => $event->getDiensten(),
            'amoc_landen' => $this->getAmocLanden(),
        ];
    }

    protected function getAmocLanden()
    {
        return $this->getDoctrine()->getEntityManager()->getRepository(Land::class)
            ->createQueryBuilder('land')
            ->innerJoin(AmocLand::class, 'amoc', 'WITH', 'amoc.land = land')
            ->getquery()
            ->getResult()
        ;
    }

    private function doSearch(Request $request)
    {
        $filterForm = $this->getForm(AppKlantFilterType::class, null, [
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
                $mwKlant = $this->dao->find($klantId);
                if ($mwKlant) {
                    return $this->redirectToView($mwKlant);
                }
            }
        }

        $mwKlant = $klant;
        $creationForm = $this->getForm(KlantType::class, $mwKlant);
        $creationForm->handleRequest($request);

        if ($creationForm->isSubmitted() && $creationForm->isValid()) {
            try {
                $this->dao->create($mwKlant);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');

                return $this->redirectToView($mwKlant);
            } catch (\Exception $e) {
                $message = $this->container->getParameter('kernel.debug') ? $e->getMessage() : 'Er is een fout opgetreden.';
                $this->addFlash('danger', $message);
            }

            return $this->redirectToIndex();
        }

        return [
            'entity' => $mwKlant,
            'creationForm' => $creationForm->createView(),
            'amoc_landen' => $this->getAmocLanden(),
        ];
    }
}
