<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Locatie;
use InloopBundle\Form\LocatieFilterType;
use InloopBundle\Form\LocatieType;
use InloopBundle\Service\LocatieDao;
use InloopBundle\Service\LocatieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/locaties")
 * @Template
 */
class LocatiesController extends AbstractController
{
    protected $entityName = 'locatie';
    protected $entityClass = Locatie::class;
    protected $formClass = LocatieType::class;
    protected $filterFormClass = LocatieFilterType::class;
    protected $baseRouteName = 'inloop_locaties_';

    /**
     * @var LocatieDao
     */
    protected $dao;

    /**
     * @param LocatieDao $dao
     */
    public function __construct(LocatieDao $dao)
    {
        $this->dao = $dao;
    }

}
