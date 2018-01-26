<?php

namespace GaBundle\Event;

use GaBundle\Entity\Intake as GaIntake;
use GaBundle\Entity\Lidmaatschap;
use GaBundle\Service\GroepDaoInterface;
use GaBundle\Service\klantdossierDaoInterface;
use GaBundle\Service\LidmaatschapDaoInterface;
use IzBundle\Entity\Intake as IzIntake;
use IzBundle\Entity\IzKlant;
use IzBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class IzIntakeSubscriber implements EventSubscriberInterface
{
    /**
     * @var klantdossierDaoInterface
     */
    private $klantdossierDao;

    /**
     * @var GroepDaoInterface
     */
    private $groepDao;

    /**
     * @var LidmaatschapDaoInterface
     */
    private $lidmaatschapDao;

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
        KlantdossierDaoInterface $klantdossierDao,
        GroepDaoInterface $groepDao,
        LidmaatschapDaoInterface $lidmaatschapDao,
        $erOpUitGroepId
    ) {
        $this->klantdossierDao = $klantdossierDao;
        $this->groepDao = $groepDao;
        $this->lidmaatschapDao = $lidmaatschapDao;
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

    private function createGaIntake(IzIntake $izIntake)
    {
        $klant = $izIntake->getIzDeelnemer()->getKlant();

        $gaIntake = $this->klantdossierDao->findOneByKlant($klant);
        if ($gaIntake instanceof \GaBundle\Entity\Intake) {
            return;
        }

        $gaIntake = new GaIntake();
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

        $this->klantdossierDao->create($gaIntake);
    }

    private function createErOpUitLidmaatschap(IzIntake $izIntake)
    {
        $klant = $izIntake->getIzDeelnemer()->getKlant();
        $groep = $this->groepDao->find($this->erOpUitGroepId);

        $lidmaatschap = $this->lidmaatschapDao->findOneByGroepAndKlant($groep, $klant);
        if ($lidmaatschap instanceof Lidmaatschap) {
            return;
        }

        $lidmaatschap = new Lidmaatschap($groep, $klant);
        $lidmaatschap
            ->setCommunicatieEmail(true)
            ->setCommunicatiePost(true)
            ->setCommunicatieTelefoon(true)
        ;

        $this->lidmaatschapDao->create($lidmaatschap);
    }
}
