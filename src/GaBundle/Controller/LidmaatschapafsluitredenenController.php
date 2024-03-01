<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use GaBundle\Entity\LidmaatschapAfsluitreden;
use GaBundle\Form\LidmaatschapAfsluitredenType;
use GaBundle\Service\LidmaatschapAfsluitredenDaoInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lidmaatschapafsluitredenen")
 */
class LidmaatschapafsluitredenenController extends AbstractController
{
    protected $title = 'Afsluitredenen lidmaatschap';
    protected $entityName = 'afsluitreden lidmaatschap';
    protected $entityClass = LidmaatschapAfsluitreden::class;
    protected $formClass = LidmaatschapAfsluitredenType::class;
    protected $baseRouteName = 'ga_lidmaatschapafsluitredenen_';

    /**
     * @var LidmaatschapAfsluitredenDaoInterface
     */
    protected $dao;

    public function __construct(LidmaatschapAfsluitredenDaoInterface $dao)
    {
        $this->dao = $dao;
    }
}
