<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Controller\DisableIndexActionTrait;
use AppBundle\Entity\Klant;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use Doctrine\ORM\EntityNotFoundException;
use InloopBundle\Service\VerslagDaoInterface;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\BinnenViaOptieKlant;
use MwBundle\Entity\MwDossierStatus;
use MwBundle\Entity\Project;
use MwBundle\Entity\Verslag;
use MwBundle\Form\VerslagType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/verslagen")
 *
 * @Template
 */
class VerslagenController extends AbstractController
{
    use DisableIndexActionTrait;

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
     *
     * @ParamConverter("klant", class="AppBundle\Entity\Klant")
     */
    public function addAction(Request $request)
    {
        $klant = $request->get('klant');

        if (!$klant->getHuidigeMwStatus() instanceof Aanmelding) {

            /**
             * When verslagen are added to a klant, this is formally an MW activity.
             *Therefore, there should be an MW file present. If this is not the case,
             * one is created so the verslag can be added.
             */
            $mwAanmelding = $this->createMwDossier($klant, $request);
            $klant->setHuidigeMwStatus($mwAanmelding);
            $this->entityManager->persist($klant);
            $this->entityManager->flush();
            $this->addFlash("info", "Klant had geen MW dossier. Automatisch MW dossier aangemaakt zodat het verslag kan worden toegevoegd.");
        }

        $entity = new Verslag($klant, Verslag::TYPE_INLOOP);

        return $this->processForm($request, $entity);
    }

    private function createMwDossier(Klant $klant, Request $request): MwDossierStatus
    {
        $mwProjectRepo = $this->entityManager->getRepository(Project::class);
        $defaultProject = $mwProjectRepo->findOneBy(['naam'=>'AMW']);

        $binnenViaRepo = $this->entityManager->getRepository(BinnenViaOptieKlant::class);
        $defaultBinnenViaOptie = $binnenViaRepo->findOneBy(['naam'=>'Inloophuizen']);

        $mwDossier = new Aanmelding($this->getMedewerker());
        $mwDossier->setDatum(new \DateTime());
        $mwDossier->setKlant($klant);
        $mwDossier->setLocatie($request->get('locatie'));
        $mwDossier->setProject($defaultProject);
        $mwDossier->setBinnenViaOptieKlant($defaultBinnenViaOptie);

        return $mwDossier;
    }
    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);
        /* Alleen mensen van MW mogen dat soort verslagen bewerken. Mocht iemand de URL manipuleren.... */
        if (1 == $entity->getType() && !$this->isGranted('ROLE_MW')) {
            throw new EntityNotFoundException('Kan verslag niet vinden.');
        }

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
