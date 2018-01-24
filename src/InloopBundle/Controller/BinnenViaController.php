<?php

namespace InloopBundle\Controller;

use AppBundle\Entity\Vrijwilliger as AppVrijwilliger;
use AppBundle\Form\VrijwilligerFilterType as AppVrijwilligerFilterType;
use InloopBundle\Entity\Vrijwilliger;
use InloopBundle\Form\VrijwilligerFilterType;
use InloopBundle\Form\VrijwilligerType;
use InloopBundle\Service\VrijwilligerDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Controller\AbstractController;
use AppBundle\Export\ExportInterface;
use Symfony\Component\Form\FormError;
use InloopBundle\Entity\BinnenVia;
use InloopBundle\Form\BinnenViaType;

/**
 * @Route("/admin/binnenvia")
 */
class BinnenViaController extends AbstractController
{
    protected $title = 'Binnen via-opties';
    protected $entityName = 'binnen via-optie';
    protected $entityClass = BinnenVia::class;
    protected $formClass = BinnenViaType::class;
    protected $baseRouteName = 'inloop_binnenvia_';

    /**
     * @var BinnenViaDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\BinnenViaDao")
     */
    protected $dao;
}
