<?php

namespace IzBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Export\AbstractExport;
use IzBundle\Entity\Intervisiegroep;
use IzBundle\Service\IntervisiegroepDaoInterface;
use IzBundle\Form\IntervisiegroepType;
use IzBundle\Form\IntervisiegroepFilterType;
use Symfony\Component\HttpFoundation\Request;
use IzBundle\Form\IzEmailMessageType;
use AppBundle\Controller\AbstractChildController;
use IzBundle\Form\LidmaatschapType;
use AppBundle\Exception\AppException;
use IzBundle\Entity\Lidmaatschap;

/**
 * @Route("/lidmaatschappen")
 */
class LidmaatschappenController extends AbstractChildController
{
    protected $title = 'Lidmaatschappen';
    protected $entityName = 'lidmaatschap';
    protected $entityClass = Lidmaatschap::class;
    protected $formClass = LidmaatschapType::class;
    protected $addMethod = 'addLidmaatschap';
    protected $baseRouteName = 'iz_lidmaatschappen_';
    protected $disabledActions = ['index', 'view', 'edit', 'delete'];

    /**
     * @var IntervisiegroepDaoInterface
     *
     * @DI\Inject("IzBundle\Service\IntervisiegroepDao")
     */
    protected $dao;

    /**
     * @var \ArrayObject
     *
     * @DI\Inject("iz.lidmaatschap.entities")
     */
    protected $entities;
}
