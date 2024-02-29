<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use MwBundle\Entity\AfsluitredenKlant;
use MwBundle\Entity\Resultaat;
use MwBundle\Form\AfsluitredenKlantType;
use MwBundle\Form\ResultaatType;
use MwBundle\Service\AfsluitredenKlantDao;
use MwBundle\Service\ResultaatDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/resultaten")
 */
class ResultatenController extends AbstractController
{
    protected $title = 'Resultaten klanten';
    protected $entityName = 'resultaat';
    protected $entityClass = Resultaat::class;
    protected $formClass = ResultaatType::class;
    protected $baseRouteName = 'mw_resultaten_';
    protected $disabledActions = ['view', 'delete'];

    /**
     * @var ResultaatDaoInterface
     */
    protected $dao;

    public function __construct(ResultaatDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
