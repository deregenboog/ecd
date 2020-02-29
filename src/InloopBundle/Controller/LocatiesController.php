<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Locatie;
use InloopBundle\Form\LocatieType;
use InloopBundle\Service\LocatieDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/locaties")
 * @Template
 */
class LocatiesController extends AbstractController
{
    protected $title = 'Locaties';
    protected $entityName = 'locatie';
    protected $entityClass = Locatie::class;
    protected $formClass = LocatieType::class;
    protected $baseRouteName = 'inloop_locaties_';

    /**
     * @var LocatieDaoInterface
     */
    protected $dao;

    public function __construct()
    {
        $this->dao = $this->get("InloopBundle\Service\LocatieDao");
    }
}
