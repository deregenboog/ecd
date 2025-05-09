<?php

namespace MwBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Intake;
use InloopBundle\Form\IntakeType;
use MwBundle\Form\WachtlijstFilterType;
use MwBundle\Service\WachtlijstDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/wachtlijst")
 *
 * @Template
 */
class WachtlijstController extends AbstractController
{
    protected $title = 'Wachtlijst (intakes / verslag)';

    protected $filterFormClass = WachtlijstFilterType::class;
    protected $baseRouteName = 'mw_wachtlijst_';

    /**
     * @var WachtlijstDaoInterface
     */
    protected $dao;

    /**
     * Wachtlijst zijn eigenlijk twee lijsten.
     * Een op basis van intakes van inloop, waarbij de intakelocatie op een wachtlijst is. Dit geldt voor T6 locaties.
     * Een op basis van verslagen van MW, waarbij de verslaglocatie een inlooplocatie is.
     *
     * Dit is zo vanwege de bedrijfsprocessen waarbij iemand die bij T6 op de wachtlijst echt nog niet wordt gezien door een MW-er
     * Terwijl dat bij STED wachtlijsten al wel zo is, en er dus daardoor al een dossier is.
     */
    public function __construct(WachtlijstDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @Route("/")
     *
     * @Template
     */
    public function indexAction(Request $request)
    {
        $ret = parent::indexAction($request);
        if (!$request->get('wachtlijst_filter')) {
            $this->addFlash('warning', 'De onderstaande lijst is die op basis van Intakes (T6). Als je een STED wachtlijst selecteert worden deze pas in de lijst geladen. (Omdat de wachtlijsten en combinatie zijn van inloop intakes of mw verslagen).');
        }

        // later cookie toevoegen om filter te onthouden
        return $ret;
    }
}
