<?php

namespace AdminBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Medewerker;
use AppBundle\Form\MedewerkerType;
use AppBundle\Service\MedewerkerDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/gebruikers")
 */
class UsersController extends AbstractController
{
    protected $title = 'Gebruikers';
    protected $entityName = 'gebruiker';
    protected $entityClass = Medewerker::class;
    protected $formClass = MedewerkerType::class;
    protected $baseRouteName = 'admin_users_';

    /**
     * @var MedewerkerDaoInterface
     *
     * @DI\Inject("AppBundle\Service\MedewerkerDao")
     */
    protected $dao;
}
