<?php

namespace OekBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use OekBundle\Entity\Groep;
use OekBundle\Form\GroepType;
use OekBundle\Service\GroepDao;
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
     * @var GroepDao
     */
    protected $dao;

    /**
     * @param GroepDao $dao
     */
    public function __construct(GroepDao $dao)
    {
        $this->dao = $dao;
    }


}
