<?php

namespace IzBundle\Controller;

use AppBundle\Export\AbstractExport;
use IzBundle\Entity\Hulpaanbod;
use IzBundle\Form\HulpaanbodFilterType;
use IzBundle\Service\HulpaanbodDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\AbstractChildController;
use IzBundle\Entity\Hulpvraag;
use IzBundle\Form\HulpaanbodType;

/**
 * @Route("/hulpaanbiedingen")
 */
class HulpaanbiedingenController extends AbstractChildController
{
    protected $title = 'Hulpaanbiedingen';
    protected $entityName = 'hulpaanbod';
    protected $entityClass = Hulpaanbod::class;
    protected $formClass = HulpaanbodType::class;
    protected $filterFormClass = HulpaanbodFilterType::class;
    protected $baseRouteName = 'iz_hulpaanbiedingen_';

    /**
     * @var HulpaanbodDaoInterface
     *
     * @DI\Inject("IzBundle\Service\HulpaanbodDao")
     */
    protected $dao;

    /**
     * @var AbstractExport
     *
     * @DI\Inject("iz.export.hulpaanbiedingen")
     */
    protected $export;

    protected function addParams($entity)
    {
        return [
            'kandidaten' => $this->getEntityManager()->getRepository(Hulpvraag::class)->findMatching($entity),
        ];
    }
}
