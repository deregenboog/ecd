<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Gebruikswijze;
use InloopBundle\Form\GebruikswijzeType;
use InloopBundle\Service\GebruikswijzeDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/gebruikswijzen")
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
     *
     * @DI\Inject("InloopBundle\Service\GebruikswijzeDao")
     */
    protected $dao;
}
