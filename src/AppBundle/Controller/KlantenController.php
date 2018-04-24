<?php

namespace AppBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Export\AbstractExport;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\KlantFilterType;
use AppBundle\Entity\Klant;
use AppBundle\Event\Events;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Form\KlantType;
use AppBundle\Service\KlantDaoInterface;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
    protected $baseRouteName = 'app_klanten_';

    /**
     * @var KlantDaoInterface
     *
     * @DI\Inject("AppBundle\Service\KlantDao")
     */
    protected $dao;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("app.export.klanten")
     */
    protected $export;

    /**
     * @Template
     */
    public function _dienstenAction($id)
    {
        $event = new DienstenLookupEvent($id);
        $this->get('event_dispatcher')->dispatch(Events::DIENSTEN_LOOKUP, $event);

        return [
            'diensten' => $event->getDiensten(),
        ];
    }

    /**
     * @Template
     */
    public function _zrmsAction($id)
    {
        $entity = $this->dao->find($id);

        return [
            'klant' => $entity,
        ];
    }

    protected function addParams(Klant $entity)
    {
        $event = new DienstenLookupEvent($entity->getId());
        $this->get('event_dispatcher')->dispatch(Events::DIENSTEN_LOOKUP, $event);

        return [
            'diensten' => $event->getDiensten(),
        ];
    }
}
