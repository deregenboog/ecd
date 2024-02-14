<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Gebruikswijze;
use InloopBundle\Form\GebruikswijzeType;
use InloopBundle\Service\GebruikswijzeDao;
use InloopBundle\Service\GebruikswijzeDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
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
     * @var GebruikswijzeDao
     */
    protected $dao;

    /**
     * @param GebruikswijzeDao $dao
     */
    public function __construct(GebruikswijzeDao $dao)
    {
        $this->dao = $dao;
    }


}
