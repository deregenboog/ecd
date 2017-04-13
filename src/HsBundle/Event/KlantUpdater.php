<?php
namespace HsBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;
use HsBundle\Entity\Klant;
use Doctrine\Common\Collections\Criteria;
use HsBundle\Entity\Klus;
use Doctrine\ORM\EntityManager;
use HsBundle\Entity\Factuur;
use HsBundle\Entity\Betaling;

class KlantUpdater implements EventSubscriber
{

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof Klus && $entity->getKlant()) {
            $this->updateStatus($entity->getKlant(), $args->getEntityManager());
        }

        if ($entity instanceof Factuur) {
            $this->updateSaldo($entity->getKlant(), $args->getEntityManager());
        }

        if ($entity instanceof Betaling) {
            $this->updateSaldo($entity->getFactuur()->getKlant(), $args->getEntityManager());
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->postPersist($args);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $this->postPersist($args);
    }

    private function updateStatus(Klant $klant, EntityManager $manager)
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->isNull('einddatum'))
            ->orWhere(Criteria::expr()->gte('einddatum', new \DateTime('today')))
        ;

        $actief = !$klant->isOnHold() && count($klant->getKlussen()->matching($criteria)) > 0;
        $klant->setActief($actief);

        $manager->flush($klant);
    }

    private function updateSaldo(Klant $klant, EntityManager $manager)
    {
        $klant->setSaldo($klant->getBetaald() - $klant->getGefactureerd());

        $manager->flush($klant);
    }
}
