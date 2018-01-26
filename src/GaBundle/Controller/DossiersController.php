<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use GaBundle\Form\DossierCloseType;
use GaBundle\Form\DossierOpenType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dossiers")
 * @Template
 */
abstract class DossiersController extends AbstractController
{
    protected $disabledActions = ['edit', 'delete'];

    /**
     * @Route("/{id}/view/verslagen")
     */
    public function viewVerslagenAction(Request $request, $id)
    {
        return $this->viewAction($request, $id);
    }

    /**
     * @Route("/{id}/view/groepen")
     */
    public function viewGroepenAction(Request $request, $id)
    {
        return $this->viewAction($request, $id);
    }

    /**
     * @Route("/{id}/view/activiteiten")
     */
    public function viewActiviteitenAction(Request $request, $id)
    {
        return $this->viewAction($request, $id);
    }

    /**
     * @Route("/{id}/view/afsluiting")
     */
    public function viewAfsluitingAction(Request $request, $id)
    {
        return $this->viewAction($request, $id);
    }

    /**
     * @Route("/{id}/open")
     */
    public function openAction(Request $request, $id)
    {
        $dossier = $this->dao->find($id);
        $dossier->open();
        $this->formClass = DossierOpenType::class;

        return $this->processForm($request, $dossier);
    }

    /**
     * @Route("/{id}/close")
     */
    public function closeAction(Request $request, $id)
    {
        $dossier = $this->dao->find($id);
        $dossier->close();
        $this->formClass = DossierCloseType::class;

        return $this->processForm($request, $dossier);
    }
}
