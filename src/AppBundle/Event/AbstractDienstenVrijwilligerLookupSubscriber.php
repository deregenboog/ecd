<?php

namespace AppBundle\Event;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractDienstenVrijwilligerLookupSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var UrlGeneratorInterface
     */
    protected $generator;

    public static function getSubscribedEvents(): array
    {
        return [
            Events::DIENSTEN_VRIJWILLIGER_LOOKUP => ['provideDienstenInfo'],
        ];
    }

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $generator)
    {
        $this->entityManager = $entityManager;
        $this->generator = $generator;
    }

    abstract public function provideDienstenInfo(DienstenVrijwilligerLookupEvent $event): void;
}
