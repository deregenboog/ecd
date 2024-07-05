<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Gebruikswijze;
use InloopBundle\Form\GebruikswijzeType;
use InloopBundle\Service\GebruikswijzeDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/gebruikswijzen")
 *
 * @Template
 */
class GebruikswijzenController extends AbstractController
{
    protected $title = 'Verslavingsgebruikswijzen';
    protected $entityName = 'gebruikswijze';
    protected $entityClass = Gebruikswijze::class;
    protected $formClass = GebruikswijzeType::class;
    protected $baseRouteName = 'inloop_gebruikswijzen_';

    /**
     * @var GebruikswijzeDaoInterface
     */
    protected $dao;

    public function __construct(GebruikswijzeDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
