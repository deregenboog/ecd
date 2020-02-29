<?php

namespace PfoBundle\Controller;

use AppBundle\Controller\AbstractController;
use PfoBundle\Entity\AardRelatie;
use PfoBundle\Form\AardRelatieType;
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
     * @var AardRelatieDaoInterface
     */
    protected $dao;

    public function __construct()
    {
        $this->dao = $this->get("PfoBundle\Service\AardRelatieDao");
    }
}
