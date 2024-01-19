<?php

namespace DagbestedingBundle\Event;

use DagbestedingBundle\Entity\Deelnemer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class GeenActiveringSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public static function getSubscribedEvents(): array
    {
        if (class_exists(\InloopBundle\Event\Events::class)) {
            return [
                \InloopBundle\Event\Events::GEEN_ACTIVERING => ['provideIds'],
            ];
        }
    }

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function provideIds(GenericEvent $event)
    {
        $klantIds = $event->getSubject();

        $builder = $this->em->getRepository(Deelnemer::class)->createQueryBuilder('deelnemer')
            ->select('klant.id')
            ->innerJoin('deelnemer.klant', 'klant')
            ->andWhere('deelnemer.aanmelddatum <= DATE(:today)')
            ->andWhere('deelnemer.afsluitdatum IS NULL OR deelnemer.afsluitdatum > DATE(:today)')
            ->andWhere('klant.id IN (:klant_ids)')
            ->setParameter('today', new \DateTime('today'))
            ->setParameter('klant_ids', $klantIds)
        ;
        $newIds = array_map(function ($item) {
            return $item['id'];
        }, $builder->getQuery()->getResult());

        $currentIds = $event->getArgument('geen_activering_klant_ids');

        $event->setArgument('geen_activering_klant_ids', array_merge($currentIds, $newIds));
    }
}
