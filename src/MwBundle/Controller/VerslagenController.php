<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;

use Doctrine\ORM\EntityNotFoundException;
use MwBundle\Service\InventarisatieDao;
use MwBundle\Service\KlantDao;
use MwBundle\Service\KlantDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use MwBundle\Entity\Verslag;
use MwBundle\Entity\Aanmelding;
use MwBundle\Form\VerslagModel;
use MwBundle\Form\VerslagType;
use MwBundle\Service\InventarisatieDaoInterface;
use MwBundle\Service\VerslagDao;
use MwBundle\Service\VerslagDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Form;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/verslagen")
 * @Template
 */
class VerslagenController extends AbstractController
{
    protected $entityName = 'verslag';
    protected $entityClass = Verslag::class;
    protected $formClass = VerslagType::class;
    protected $baseRouteName = 'mw_verslagen_';

    /**
     * @var VerslagDao
     */
    protected $dao;

    /**
     * @var KlantDao
     */
    protected $klantDao;

    /**
     * @var InventarisatieDao
     */
    protected $inventarisatieDao;

    /**
     * @var ExportInterface
     *

     */
    protected $export;

    /**
     * @param VerslagDao $dao
     * @param KlantDao $klantDao
     * @param InventarisatieDao $inventarisatieDao
     * @param ExportInterface $export
     */
    public function __construct(VerslagDao $dao, KlantDao $klantDao, InventarisatieDao $inventarisatieDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->klantDao = $klantDao;
        $this->inventarisatieDao = $inventarisatieDao;
        $this->export = $export;
    }


    /**
     * @Route("/add/{klant}")
     * @ParamConverter("klant", class="AppBundle:Klant")
     */
    public function addAction(Request $request)
    {
        $klant = $request->get('klant');
        if(!$klant->getHuidigeMwStatus() instanceof Aanmelding)
        {
            throw new UserException("Kan geen verslag toevoegen aan een klant met een gesloten dossier.");
        }
        $entity = new Verslag($klant,Verslag::TYPE_MW);

        $formResult = $this->processForm($request, $entity);

        return $formResult;
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        return $this->processForm($request, $entity);
    }

    protected function addParams($entity, Request $request): array
    {
        assert($entity instanceof Verslag);

        $event = new DienstenLookupEvent($entity->getKlant()->getId());
        if ($event->getKlantId()) {
            $this->eventDispatcher->dispatch($event, Events::DIENSTEN_LOOKUP);
        }

        return [
            'diensten' => $event->getDiensten(),
        ];
    }

}
