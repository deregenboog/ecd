<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Controller\DisableIndexActionTrait;
use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use Doctrine\ORM\EntityNotFoundException;
use MwBundle\Service\KlantDaoInterface;
use MwBundle\Entity\Verslag;
use MwBundle\Entity\Aanmelding;
use MwBundle\Form\VerslagType;
use MwBundle\Service\InventarisatieDaoInterface;
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
    use DisableIndexActionTrait;

    protected $entityName = 'verslag';
    protected $entityClass = Verslag::class;
    protected $formClass = VerslagType::class;
    protected $baseRouteName = 'mw_verslagen_';

    /**
     * @var VerslagDaoInterface
     */
    protected $dao;

    /**
     * @var KlantDaoInterface
     */
    protected $klantDao;

    /**
     * @var InventarisatieDaoInterface
     */
    protected $inventarisatieDao;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct(VerslagDaoInterface $dao, KlantDaoInterface $klantDao, InventarisatieDaoInterface $inventarisatieDao, ExportInterface $export)
    {
        $this->dao = $dao;
        $this->klantDao = $klantDao;
        $this->inventarisatieDao = $inventarisatieDao;
        $this->export = $export;
    }

    /**
     * @Route("/add/{klant}")
     * @ParamConverter("klant", class="AppBundle\Entity\Klant")
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
