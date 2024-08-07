<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Exception\UserException;
use InloopBundle\Entity\Locatie;
use InloopBundle\Entity\Schorsing;
use InloopBundle\Form\LocatieFilterType;
use InloopBundle\Form\LocatieType;
use InloopBundle\Service\LocatieDaoInterface;
use InloopBundle\Service\SchorsingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/locaties")
 *
 * @Template
 */
class LocatiesController extends AbstractController
{
    protected $entityName = 'locatie';
    protected $entityClass = Locatie::class;
    protected $formClass = LocatieType::class;
    protected $filterFormClass = LocatieFilterType::class;
    protected $baseRouteName = 'inloop_locaties_';
    protected $forceRedirect = true;

    /**
     * @var LocatieDaoInterface
     */
    protected $dao;

    protected $accessStrategies = [];

    /**
     * @var SchorsingDaoInterface
     */
    protected $schorsingDao;

    public function __construct(LocatieDaoInterface $dao, array $accessStrategies, SchorsingDaoInterface $schorsingDao)
    {
        $this->dao = $dao;
        $this->accessStrategies = $accessStrategies;
        $this->schorsingDao = $schorsingDao;
    }

    /**
     * @param $locatie Locatie
     */
    protected function afterCreate($locatie): void
    {
        $this->updateSchorsingen($locatie);
    }

    /**
     * OK, new location is added, we have to check and make sure that schorsingen with all locations are updated too.
     *
     * @return void
     */
    private function updateSchorsingen(Locatie $locatie)
    {
        $locatieTypes = $locatie->getLocatieTypes();
        $alleActieveSchorsingen = $this->schorsingDao->findAllActief();
        $i = 0;
        $j = 0;
        foreach ($alleActieveSchorsingen as $schorsing) {
            /** @var Schorsing $schorsing */
            $schorsing = $schorsing;

            $schorsingLocaties = $schorsing->getLocaties();
            if (in_array($locatie, $schorsingLocaties->toArray())) {
                // already exists. so it is an edit. Perhaps inlooptype removed? then remove from schorsing to prevent future updates to go wrong as they rely on count
                $hasInloopType = false;
                foreach ($locatieTypes as $locatieType) {
                    if ('Inloop' === $locatieType->getNaam()) {
                        $hasInloopType = true;
                        break;
                    }
                }

                if (!$hasInloopType) {
                    $schorsingLocaties->remove($schorsingLocaties->indexOf($locatie));
                    $schorsing->setLocaties($schorsingLocaties);
                    $this->schorsingDao->update($schorsing);
                    ++$j;
                    continue;
                }
            }
            $allLocations = $this->dao->findAllActiveLocationsOfTypeInloop();
            // this should work as we only modify one location at a time. Goes wrong when we mess with the database of course...
            // how to make this more robust... because, when one is not geschorst for one specific location, we cannot know.
            if (count($schorsingLocaties) == (count($allLocations) - 1)) {
                // we have a schorsing with all location marked (-1, the new one we've just added)
                // now add the new location to it as well.
                $schorsingLocaties->add($locatie);
                $schorsing->setLocaties($schorsingLocaties);
                $this->schorsingDao->update($schorsing);
                ++$i;
            }
        }
        if ($i > 0) {
            $this->addFlash('info', sprintf('De locatie is aan %d lopende schorsingen toegevoegd', $i));
        }
        if ($j > 0) {
            $this->addFlash('info', sprintf("De bewerkte locatie is niet meer van het type 'inloop' en is daarom weggehaald van %d schorsinging(en). Schorsingen die deze locatie hadden aangevinkt zijn geupdate.", $j));
        }
        if (count($alleActieveSchorsingen) !== $i) {
            $this->addFlash('warning', sprintf("Let op: wanneer je een locatie aanpast denk dan goed na of er schorsingen zijn die hierdoor beinvloed kunnen worden (bv. doordat ze voor 'Alle locaties, behalve ...' gelden. De locatie kan dan niet automatisch worden toegevoegd aan die schorsing. Kijk dit zelf na!)"));
        }
    }

    protected function beforeUpdate($locatie): void
    {
        $uow = $this->entityManager->getUnitOfWork();
        $uow->computeChangeSets();
        $changeset = $uow->getEntityChangeSet($locatie);

        foreach ($this->accessStrategies as $strategy => $intakeLocaties) {
            if (isset($changeset['naam']) && array_intersect($intakeLocaties, $changeset['naam'])) {
                throw new UserException(sprintf('Let op, deze locatie heeft speciale functionaliteit in ECD. De naam mag niet aangepast worden.'));
            }
        }
    }

    protected function afterUpdate($locatie): void
    {
        $this->updateSchorsingen($locatie);
    }
}
