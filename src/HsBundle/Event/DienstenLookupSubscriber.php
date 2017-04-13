<?php

namespace HsBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Event\Events;
use AppBundle\Event\DienstenLookupEvent;
use OekBundle\Entity\OekKlant;
use HsBundle\Service\KlantDaoInterface;
use HsBundle\Entity\Klant;
use HsBundle\Service\DienstverlenerDaoInterface;
use HsBundle\Entity\Dienstverlener;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DienstenLookupSubscriber implements EventSubscriberInterface
{
    /**
     * @var DienstverlenerDaoInterface
     */
    private $dienstverlenerDao;

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    public static function getSubscribedEvents()
    {
        return [
            Events::DIENSTEN_LOOKUP => ['provideDienstenInfo'],
        ];
    }

    public function __construct(
        DienstverlenerDaoInterface $dienstverlenerDao,
        UrlGeneratorInterface $generator
    ) {
        $this->dienstverlenerDao = $dienstverlenerDao;
        $this->generator = $generator;
    }

    public function provideDienstenInfo(DienstenLookupEvent $event)
    {
        $dienstverlener = $this->dienstverlenerDao->findOneByKlant($event->getKlant());

        if ($dienstverlener instanceof Dienstverlener) {
            $event->addDienst([
                'name' => 'Homeservice',
                'url' => $this->generator->generate('hs_dienstverlener_view', ['id' => $dienstverlener->getId()]),
                'from' => $dienstverlener->getInschrijving() ? $dienstverlener->getInschrijving()->format('Y-m-d') : null,
                'to' => null,
                'type' => 'date',
                'value' => '',
            ]);
        }
    }
}
