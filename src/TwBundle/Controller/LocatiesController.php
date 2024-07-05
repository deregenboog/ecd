<?php

namespace TwBundle\Controller;

use AppBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use TwBundle\Entity\Locatie;
use TwBundle\Form\LocatieType;
use TwBundle\Service\LocatieDaoInterface;

/**
 * @Route("/locaties")
 *
 * @Template
 */
class LocatiesController extends AbstractController
{
    protected $entityName = 'locatie';
    protected $entityClass = Locatie::class;
    protected $formClass = LocatieType::class;
    protected $baseRouteName = 'tw_locaties_';

    /**
     * @var LocatieDaoInterface
     */
    protected $dao;

    public function __construct(LocatieDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
