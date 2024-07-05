<?php

namespace ClipBundle\Controller;

use AppBundle\Controller\AbstractController;
use ClipBundle\Entity\Locatie;
use ClipBundle\Form\LocatieType;
use ClipBundle\Service\LocatieDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/locaties")
 *
 * @Template
 */
class LocatiesController extends AbstractController
{
    protected $entityName = 'locatie';
    protected $entityClass = Locatie::class;
    protected $formClass = LocatieType::class;
    protected $baseRouteName = 'clip_locaties_';

    /**
     * @var LocatieDaoInterface
     */
    protected $dao;

    public function __construct(LocatieDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
