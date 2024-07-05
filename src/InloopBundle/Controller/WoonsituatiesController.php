<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Woonsituatie;
use InloopBundle\Form\WoonsituatieType;
use InloopBundle\Service\WoonsituatieDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/woonsituaties")
 *
 * @Template
 */
class WoonsituatiesController extends AbstractController
{
    protected $entityName = 'woonsituatie';
    protected $entityClass = Woonsituatie::class;
    protected $formClass = WoonsituatieType::class;
    protected $baseRouteName = 'inloop_woonsituaties_';

    /**
     * @var WoonsituatieDaoInterface
     */
    protected $dao;

    public function __construct(WoonsituatieDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
