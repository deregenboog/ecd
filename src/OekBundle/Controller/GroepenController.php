<?php

namespace OekBundle\Controller;

use AppBundle\Controller\AbstractController;
use OekBundle\Entity\Groep;
use OekBundle\Form\GroepType;
use OekBundle\Service\GroepDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/groepen")
 */
class GroepenController extends AbstractController
{
    protected $entityName = 'groep';
    protected $entityClass = Groep::class;
    protected $formClass = GroepType::class;
    protected $baseRouteName = 'oek_groepen_';

    /**
     * @var GroepDaoInterface
     */
    protected $dao;

    public function __construct(GroepDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
