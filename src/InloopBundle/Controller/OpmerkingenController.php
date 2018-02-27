<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use InloopBundle\Entity\Schorsing;
use InloopBundle\Form\SchorsingFilterType;
use InloopBundle\Form\SchorsingType;
use InloopBundle\Service\SchorsingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Opmerking;
use AppBundle\Entity\Klant;
use InloopBundle\Entity\Locatie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use InloopBundle\Form\OpmerkingType;

/**
 * @Route("/opmerkingen")
 */
class OpmerkingenController extends AbstractController
{
    protected $title = 'Opmerkingen';
    protected $entityName = 'opmerking';
    protected $entityClass = Opmerking::class;
    protected $formClass = OpmerkingType::class;
    protected $baseRouteName = 'inloop_opmerkingen_';
    protected $disabledActions = ['view', 'edit'];

    /**
     * @var OpmerkingDaoInterface
     *
     * @DI\Inject("InloopBundle\Service\OpmerkingDao")
     */
    protected $dao;

    /**
     * @Route("/{klant}/{locatie}", requirements={"klant" = "\d+", "locatie" = "\d+"})
     */
    public function indexAction(Request $request, Klant $klant, Locatie $locatie = null)
    {
        return [
            'klant' => $klant,
            'locatie' => $locatie,
        ];
    }

    /**
     * @Route("/{opmerking}/updateGezien")
     * @Method("POST")
     */
    public function updateGezienAction(Request $request, Opmerking $opmerking)
    {
        $opmerking->setGezien(!$opmerking->isGezien());
        $this->dao->update($opmerking);

        return new JsonResponse(['gezien' => $opmerking->isGezien()]);
    }

    /**
     * @Route("/add/{klant}")
     */
    public function addAction(Request $request, Klant $klant)
    {
        $opmerking = new Opmerking($klant);

        return $this->processForm($request, $opmerking);
    }

    /**
     * @Route("/{opmerking}/delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, Opmerking $opmerking)
    {
        $this->dao->delete($opmerking);

        return new JsonResponse();
    }
}
