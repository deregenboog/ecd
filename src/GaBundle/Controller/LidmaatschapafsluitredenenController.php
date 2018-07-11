<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use GaBundle\Entity\LidmaatschapAfsluitreden;
use GaBundle\Form\LidmaatschapAfsluitredenType;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
     *
     * @DI\Inject("GaBundle\Service\LidmaatschapAfsluitredenDao")
     */
    protected $dao;
}
