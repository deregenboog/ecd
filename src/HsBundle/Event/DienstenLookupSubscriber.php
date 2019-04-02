<?php

namespace HsBundle\Event;

use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use AppBundle\Model\Dienst;
use HsBundle\Entity\Dienstverlener;
use HsBundle\Service\DienstverlenerDaoInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
            $dienst = new Dienst(
                'Homeservice',
                $this->generator->generate('hs_dienstverleners_view', ['id' => $dienstverlener->getId()])
            );

            if ($dienstverlener->getInschrijving()) {
                $dienst->setVan($dienstverlener->getInschrijving());
            }

            $event->addDienst($dienst);
        }
    }
}
