<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Export\ExportInterface;
use AppBundle\Form\KlantType;
use AppBundle\Service\KlantDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use MwBundle\Entity\Document;
use MwBundle\Entity\Info;
use MwBundle\Form\InfoType;
use MwBundle\Form\KlantFilterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     *
     * @DI\Inject("MwBundle\Service\KlantDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("mw.export.klanten")
     */
    protected $export;

    /**
     * @Route("/{klant}/info")
     */
    public function infoEditAction(Request $request, Klant $klant)
    {
        $em = $this->getEntityManager();
        $info = $em->getRepository(Info::class)->findOneBy(['klant' => $klant]);
        if (!$info) {
            $info = new Info($klant);
        }

        $form = $this->createForm(InfoType::class, $info);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if (!$entity->getId()) {
                    $em->persist($info);
                }
                $em->flush($info);
                $this->addFlash('success', ucfirst($this->entityName).' is opgeslagen.');
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
            $this->get('event_dispatcher')->dispatch(Events::DIENSTEN_LOOKUP, $event);
        }

        return [
            'diensten' => $event->getDiensten(),
        ];
    }
}
