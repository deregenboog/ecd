<?php

namespace MwBundle\Controller;

use AppBundle\Entity\Klant;
use AppBundle\Export\ExportInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\AbstractController;
use AppBundle\Form\KlantType;
use AppBundle\Service\KlantDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use MwBundle\Form\KlantFilterType;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;

/**
 * @Route("/klanten")
 */
class KlantenController extends AbstractController
{
    protected $title = 'Klanten';
    protected $entityName = 'klant';
    protected $entityClass = Klant::class;
    protected $formClass = KlantType::class;
    protected $filterFormClass = KlantFilterType::class;
    protected $baseRouteName = 'mw_klanten_';

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("MwBundle\Service\KlantDao")
     */
    protected $dao;

    /**
     * @var ExportInterface
     *
     * @DI\Inject("mw.export.klanten")
     */
    protected $export;

    protected function addParams(Klant $entity)
    {
        $event = new DienstenLookupEvent($entity->getId());
        $this->get('event_dispatcher')->dispatch(Events::DIENSTEN_LOOKUP, $event);

        return [
            'diensten' => $event->getDiensten(),
        ];
    }
}
