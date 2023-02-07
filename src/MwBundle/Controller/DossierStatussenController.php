<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractChildController;
use AppBundle\Service\AbstractDao;
use MwBundle\Entity\Aanmelding;
use MwBundle\Entity\Afsluiting;
use MwBundle\Entity\DossierStatus;
use MwBundle\Entity\IntakeAdministratie;
use MwBundle\Entity\IntakeAlgemeen;
use MwBundle\Entity\IntakeGezin;
use MwBundle\Entity\IntakeHuisvesting;
use MwBundle\Entity\IntakeInkomen;
use MwBundle\Entity\IntakeVerwachting;
use MwBundle\Entity\IntakeWelzijn;
use MwBundle\Form\AanmeldingType;
use MwBundle\Form\AfsluitingType;
use MwBundle\Form\IntakeAdministratieType;
use MwBundle\Form\IntakeAlgemeenType;
use MwBundle\Form\IntakeGezinType;
use MwBundle\Form\IntakeHuisvestingType;
use MwBundle\Form\IntakeInkomenType;
use MwBundle\Form\IntakeVerwachtingType;
use MwBundle\Form\IntakeWelzijnType;
use MwBundle\Service\DossierStatusDao;
use MwBundle\Service\KlantDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dossierstatussen")
 */
class DossierStatussenController extends AbstractChildController
{
    protected $baseRouteName = 'mw_klanten_';

    /**
     * @var \ArrayObject
     */
    protected $entities;

    /**
     * @var string
     */
    protected $addMethod = 'addDossierStatus';

    /**
     * @var string
     */
    protected $deleteMethod;

    /**
     * @var bool
     */
    protected $allowEmpty = false;

    protected $subnavigation = '@Mw/subnavigation.html.twig';
    protected $disabledActions = ['index', 'view', 'delete'];

    public function __construct(DossierStatusDao $dao, \ArrayObject $entities)
    {
        $this->dao = $dao;
        $this->entities = $entities;
    }

    private $entityNames = [
        Aanmelding::class => 'Aanmelding',
        Afsluiting::class => 'Afsluiting',
        IntakeAdministratie::class => 'Intake Administratie',
        IntakeAlgemeen::class => 'Intake Algemeen',
        IntakeGezin::class => 'Intake Gezin',
        IntakeHuisvesting::class => 'Intake Huisvesting',
        IntakeInkomen::class => 'Intake Inkomen',
        IntakeVerwachting::class => 'Intake Verwachting',
        IntakeWelzijn::class => 'Intake Welzijn',
    ];

    private $formClasses = [
        Aanmelding::class => AanmeldingType::class,
        Afsluiting::class => AfsluitingType::class,
        IntakeAdministratie::class => IntakeAdministratieType::class,
        IntakeAlgemeen::class => IntakeAlgemeenType::class,
        IntakeGezin::class => IntakeGezinType::class,
        IntakeHuisvesting::class => IntakeHuisvestingType::class,
        IntakeInkomen::class => IntakeInkomenType::class,
        IntakeVerwachting::class => IntakeVerwachtingType::class,
        IntakeWelzijn::class => IntakeWelzijnType::class,
    ];

    /**
     * @Route("/add")
     * @Template
     */
    public function addAction(Request $request)
    {
        switch ($request->get('entity')) {
            case 'aanmelding':
                $this->entityClass = Aanmelding::class;
                break;
            case 'afsluiting':
                $this->entityClass = Afsluiting::class;
                break;
            case 'intake_administratie':
                $this->entityClass = IntakeAdministratie::class;
                break;
            case 'intake_algemeen':
                $this->entityClass = IntakeAlgemeen::class;
                break;
            case 'intake_gezin':
                $this->entityClass = IntakeGezin::class;
                break;
            case 'intake_huisvesting':
                $this->entityClass = IntakeHuisvesting::class;
                break;
            case 'intake_inkomen':
                $this->entityClass = IntakeInkomen::class;
                break;
            case 'intake_verwachting':
                $this->entityClass = IntakeVerwachting::class;
                break;
            case 'intake_welzijn':
                $this->entityClass = IntakeWelzijn::class;
                break;
            default:
                var_dump($request->get('entity'));
                die;
        }

        $this->entityName = $this->entityNames[$this->entityClass];
        $this->formClass = $this->formClasses[$this->entityClass];

        return parent::addAction($request);
    }

    protected function processForm(Request $request, $entity = null)
    {
        $entityClass = get_class($entity);
        $this->entityName = $this->entityNames[$entityClass];
        $this->formClass = $this->formClasses[$entityClass];

        return parent::processForm($request, $entity);
    }

    /**
     * Add entity_name explicitly here, because it's still empty
     * when event kernel.controller fires.
     */
    protected function addParams($entity, Request $request)
    {
        return [
            'entity_name' => $this->entityName,
        ];
    }
}