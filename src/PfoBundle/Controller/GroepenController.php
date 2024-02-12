<?php

namespace PfoBundle\Controller;

use AppBundle\Controller\AbstractController;
use PfoBundle\Entity\Groep;
use PfoBundle\Form\GroepType;
use PfoBundle\Service\GroepDao;
use PfoBundle\Service\GroepDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/groepen")
 */
class GroepenController extends AbstractController
{
    protected $entityName = 'groep';
    protected $entityClass = Groep::class;
    protected $formClass = GroepType::class;
    protected $baseRouteName = 'pfo_groepen_';
    protected $disabledActions = ['delete'];

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
