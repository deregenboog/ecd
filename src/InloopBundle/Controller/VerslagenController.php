<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use Doctrine\ORM\EntityNotFoundException;
use InloopBundle\Service\VerslagDaoInterface;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Verslag;
use MwBundle\Form\VerslagModel;
use MwBundle\Form\VerslagType;
use MwBundle\Service\InventarisatieDao;
use MwBundle\Service\InventarisatieDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
    protected $baseRouteName = 'inloop_verslagen_';

    /**
     * @var VerslagDaoInterface
     */
    protected $dao;

    /**
     * @var ExportInterface
     */
    protected $export;

    public function __construct(VerslagDaoInterface $dao, ExportInterface $export)
    {
        $this->dao = $dao;
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
            throw new UserException("Kan geen verslag toevoegen aan een klant zonder een lopend MW dossier.");
        }

        $entity = new Verslag($klant, Verslag::ACCESS_ALL);

        return $this->processForm($request, $entity);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        /** Alleen mensen van MW mogen dat soort verslagen bewerken. Mocht iemand de URL manipuleren....  */
        if($entity->getType() == 1 && !$this->isGranted("ROLE_MW")) throw new EntityNotFoundException("Kan verslag niet vinden.");
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
