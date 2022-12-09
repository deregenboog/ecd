<?php

namespace PfoBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use PfoBundle\Entity\AardRelatie;
use PfoBundle\Form\AardRelatieType;
use PfoBundle\Service\AardRelatieDao;
use PfoBundle\Service\AardRelatieDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/aardrelaties")
 */
class AardRelatiesController extends AbstractController
{
    protected $title = 'Aard relaties';
    protected $entityName = 'aard relatie';
    protected $entityClass = AardRelatie::class;
    protected $formClass = AardRelatieType::class;
    protected $baseRouteName = 'pfo_aardrelaties_';
    protected $disabledActions = ['delete'];

    /**
     * @var AardRelatieDao
     */
    protected $dao;

    /**
     * @param AardRelatieDao $dao
     */
    public function __construct(AardRelatieDao $dao)
    {
        $this->dao = $dao;
    }


}
