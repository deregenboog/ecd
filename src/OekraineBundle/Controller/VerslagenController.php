<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Controller\DisableIndexActionTrait;
use AppBundle\Event\DienstenLookupEvent;
use AppBundle\Event\Events;
use Doctrine\ORM\EntityNotFoundException;
use OekraineBundle\Entity\Verslag;
use OekraineBundle\Form\VerslagType;
use OekraineBundle\Service\VerslagDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/verslagen")
 *
 * @Template
 */
class VerslagenController extends AbstractChildController
{
    use DisableIndexActionTrait;
    use AccessProfileTrait;

    protected $entityName = 'verslag';
    protected $entityClass = Verslag::class;
    protected $formClass = VerslagType::class;
    protected $baseRouteName = 'oekraine_verslagen_';
    protected $addMethod = 'addVerslag';

    /**
     * @var VerslagDaoInterface
     */
    protected $dao;

    /**
     * @var \ArrayObject
     */
    protected $entities;

    public function __construct(VerslagDaoInterface $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }

    /**
     * @Route("/add")
     *
     * @Template
     *
     * @return array|mixed[]|null[]|\Symfony\Component\Form\FormView[]|\Symfony\Component\HttpFoundation\RedirectResponse|null
     */
    public function addAction(Request $request)
    {
        // afhankelijk van de rol wordt er een type ingesteld. dat werkt goed op deze manier.

        $type = Verslag::TYPE_INLOOP;

        if ($this->isGranted('ROLE_OEKRAINE_PSYCH')) {
            $type = Verslag::TYPE_PSYCH;
        } elseif ($this->isGranted('ROLE_MW')) {
            $type = Verslag::TYPE_MW;
        }

        $this->entity = new Verslag($type);

        return parent::addAction($request);
    }

    /**
     * @Route("/{id}/edit")
     */
    public function editAction(Request $request, $id)
    {
        $entity = $this->dao->find($id);

        /* Alleen mensen van MW / psychologen mogen dat soort verslagen bewerken. Mocht iemand de URL manipuleren.... */
        if (Verslag::TYPE_MW == $entity->getType() && !$this->isGranted('ROLE_MW')) {
            throw new EntityNotFoundException('MW verslag, geen rechten.');
        }
        if (Verslag::TYPE_PSYCH == $entity->getType() && !$this->isGranted('ROLE_OEKRAINE_PSYCH')) {
            throw new EntityNotFoundException('MW Psychologen verslag, geen rechten.');
        }

        return $this->processForm($request, $entity);
    }

    protected function beforeGetForm(&$type, &$data, &$options): void
    {
        $options['accessProfile'] = $this->getAccessProfile();
    }

    protected function addParams($entity, Request $request): array
    {
        assert($entity instanceof Verslag);

        $event = new DienstenLookupEvent($entity->getBezoeker()->getAppKlant()->getId());
        if ($event->getKlantId()) {
            $this->eventDispatcher->dispatch($event, Events::DIENSTEN_LOOKUP);
        }

        return [
            'diensten' => $event->getDiensten(),
            'accessProfile' => $this->getAccessProfile(),
        ];
    }
}
