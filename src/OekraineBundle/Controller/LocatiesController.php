<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractController;
use OekraineBundle\Entity\Locatie;
use OekraineBundle\Form\LocatieType;
use OekraineBundle\Service\LocatieDaoInterface;
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
    protected $baseRouteName = 'oekraine_locaties_';

    /**
     * @var LocatieDaoInterface
     */
    protected $dao;

    public function __construct(LocatieDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
