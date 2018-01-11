<?php

namespace OekBundle\Controller;

use AppBundle\Controller\SymfonyController;
use Doctrine\ORM\EntityRepository;
use OekBundle\Entity\Groep;
use OekBundle\Form\GroepType;
use AppBundle\Form\ConfirmationType;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @Route("/groepen")
 */
class GroepenController extends AbstractController
{
    protected $title = 'Groepen';
    protected $entityName = 'groep';
    protected $entityClass = Groep::class;
    protected $formClass = GroepType::class;
    protected $baseRouteName = 'oek_groepen_';

    /**
     * @var GroepDaoInterface
     *
     * @DI\Inject("OekBundle\Service\GroepDao")
     */
    protected $dao;
}
