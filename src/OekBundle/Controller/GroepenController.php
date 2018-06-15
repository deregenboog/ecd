<?php

namespace OekBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use OekBundle\Entity\Groep;
use OekBundle\Form\GroepType;
use Symfony\Component\Routing\Annotation\Route;

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
