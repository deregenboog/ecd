<?php

namespace GaBundle\Event;

use GaBundle\Entity\KlantIntake;
use GaBundle\Entity\KlantLidmaatschap;
use GaBundle\Service\GroepDaoInterface;
use GaBundle\Service\KlantIntakeDaoInterface;
use GaBundle\Service\LidmaatschapDaoInterface;
use IzBundle\Entity\Intake;
use IzBundle\Entity\IzKlant;
use IzBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class IzIntakeSubscriber implements EventSubscriberInterface
{
    /**
     * @var KlantIntakeDaoInterface
     */
    private $klantIntakeDao;

    /**
     * @var GroepDaoInterface
     */
    private $groepDao;

    /**
     * @var LidmaatschapDaoInterface
     */
    private $klantLidmaatschapDao;

    /**
     * @var int
     */
    private $erOpUitGroepId;

    public static function getSubscribedEvents()
    {
        return [
            Events::EVENT_INTAKE_CREATED => ['addToEropuit'],
        ];
    }

    public function __construct(
        KlantIntakeDaoInterface $klantIntakeDao,
        GroepDaoInterface $groepDao,
        LidmaatschapDaoInterface $klantLidmaatschapDao,
        $erOpUitGroepId
    ) {
        $this->klantIntakeDao = $klantIntakeDao;
        $this->groepDao = $groepDao;
        $this->klantLidmaatschapDao = $klantLidmaatschapDao;
        $this->erOpUitGroepId = $erOpUitGroepId;
    }

    public function addToEropuit(GenericEvent $event)
    {
        /* @var $izIntake Intake */
        $izIntake = $event->getSubject();

        if ($izIntake->getIzDeelnemer() instanceof IzKlant) {
            $this->createGaIntake($izIntake);
            $this->createErOpUitLidmaatschap($izIntake);
        }
    }

    private function createGaIntake(Intake $izIntake)
    {
        $klant = $izIntake->getIzDeelnemer()->getKlant();

        $gaIntake = $this->klantIntakeDao->findOneByKlant($klant);
        if ($gaIntake instanceof KlantIntake) {
            return;
        }

        $gaIntake = new KlantIntake();
        $gaIntake
            ->setKlant($izIntake->getIzDeelnemer()->getKlant())
            ->setGespreksverslag('Automatisch aangemaakt door IZ-inschrijving')
            ->setMedewerker($izIntake->getMedewerker())
            ->setOndernemen($izIntake->isOndernemen())
            ->setOverdag($izIntake->isOverdag())
            ->setOntmoeten($izIntake->isOntmoeten())
            ->setRegelzaken($izIntake->isRegelzaken())
            ->setIntakedatum($izIntake->getIntakeDatum())
        ;

        $this->klantIntakeDao->create($gaIntake);
    }

    private function createErOpUitLidmaatschap(Intake $izIntake)
    {
        $klant = $izIntake->getIzDeelnemer()->getKlant();
        $groep = $this->groepDao->find($this->erOpUitGroepId);

        $lidmaatschap = $this->klantLidmaatschapDao->findOneByGroepAndKlant($groep, $klant);
        if ($lidmaatschap instanceof KlantLidmaatschap) {
            return;
        }

        $lidmaatschap = new KlantLidmaatschap($groep, $klant);
        $lidmaatschap
            ->setCommunicatieEmail(true)
            ->setCommunicatiePost(true)
            ->setCommunicatieTelefoon(true)
        ;

        $this->klantLidmaatschapDao->create($lidmaatschap);
    }
}
