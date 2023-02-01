<?php

namespace BuurtboerderijBundle\Controller;

use AppBundle\Controller\AbstractController;
use BuurtboerderijBundle\Entity\Afsluitreden;
use BuurtboerderijBundle\Form\AfsluitredenType;
use BuurtboerderijBundle\Service\AfsluitredenDao;
use BuurtboerderijBundle\Service\AfsluitredenDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/afsluitredenen")
 */
class AfsluitredenenController extends AbstractController
{
    protected $entityName = 'afsluitreden';
    protected $entityClass = Afsluitreden::class;
    protected $formClass = AfsluitredenType::class;
    protected $baseRouteName = 'buurtboerderij_afsluitredenen_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var AfsluitredenDao
     */
    protected $dao;

    /**
     * @param AfsluitredenDao $dao
     */
    public function __construct(AfsluitredenDao $dao)
    {
        $this->dao = $dao;
    }


}
