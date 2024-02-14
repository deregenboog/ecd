<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Exception\AppException;
use AppBundle\Exception\UserException;
use AppBundle\Export\ExportInterface;
use Doctrine\ORM\EntityNotFoundException;
use InloopBundle\Entity\Intake;
use Knp\Component\Pager\Pagination\SlidingPagination;
use MwBundle\Exception\MwException;
use MwBundle\Form\WachtlijstFilterType;
use InloopBundle\Form\IntakeType;
use InloopBundle\Form\ToegangType;
use InloopBundle\Pdf\PdfIntake;
use InloopBundle\Security\Permissions;
use InloopBundle\Service\IntakeDaoInterface;
use MwBundle\Service\WachtlijstDao;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TwBundle\Exception\TwException;

/**
 * @Route("/wachtlijst")
 * @Template
 */
class WachtlijstController extends AbstractController
{
    protected $title = 'Wachtlijst (intakes / verslag)';
//    protected $entityName = 'intake';
//    protected $entityClass = Intake::class;
//    protected $formClass = IntakeType::class;
    protected $filterFormClass = WachtlijstFilterType::class;
    protected $baseRouteName = 'mw_wachtlijst_';

    /**
     * @var WachtlijstDao
     */
    protected $dao;

    /**
     * Wachtlijst zijn eigenlijk twee lijsten.
     * Een op basis van intakes van inloop, waarbij de intakelocatie op een wachtlijst is. Dit geldt voor T6 locaties.
     * Een op basis van verslagen van MW, waarbij de verslaglocatie een inlooplocatie is.
     *
     * Dit is zo vanwege de bedrijfsprocessen waarbij iemand die bij T6 op de wachtlijst echt nog niet wordt gezien door een MW-er
     * Terwijl dat bij STED wachtlijsten al wel zo is, en er dus daardoor al een dossier is.
     *
     *
     *
     * @param WachtlijstDao $dao
     */
    public function __construct(WachtlijstDao $dao)
    {
        $this->dao = $dao;
    }


    /**
     * @Route("/")
     * @Template
     */
    public function indexAction(Request $request)
    {
        $ret =  parent::indexAction($request);
        if(!$request->get('wachtlijst_filter'))
        {
            $this->addFlash("warning","De onderstaande lijst is die op basis van Intakes (T6). Als je een STED wachtlijst selecteert worden deze pas in de lijst geladen. (Omdat de wachtlijsten en combinatie zijn van inloop intakes of mw verslagen).");
        }
        // later cookie toevoegen om filter te onthouden
        return $ret;

    }

}
