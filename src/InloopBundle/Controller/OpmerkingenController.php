<?php

namespace InloopBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Opmerking;
use InloopBundle\Entity\Locatie;
use InloopBundle\Form\OpmerkingType;
use InloopBundle\Service\OpmerkingDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/opmerkingen")
 *
 * @Template
 */
class OpmerkingenController extends AbstractController
{
    protected $entityName = 'opmerking';
    protected $entityClass = Opmerking::class;
    protected $formClass = OpmerkingType::class;
    protected $baseRouteName = 'inloop_opmerkingen_';
    protected $disabledActions = ['view', 'edit'];

    /**
     * @var OpmerkingDaoInterface
     */
    protected $dao;

    public function __construct(OpmerkingDaoInterface $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @Route("/{klant}", requirements={"klant"="\d+"}, defaults={"locatie" = null})
     * @Route("/{klant}/{locatie}", requirements={"klant"="\d+", "locatie"="\d+"})
     *
     * @ParamConverter("klant", class="AppBundle\Entity\Klant")
     * @ParamConverter("locatie", class="InloopBundle\Entity\Locatie")
     */
    public function indexAction(Request $request)
    {
        return [
            'klant' => $request->get('klant'),
            'locatie' => $request->get('locatie'),
        ];
    }

    /**
     * @Route("/{opmerking}/updateGezien", methods={"POST"})
     */
    public function updateGezienAction(Request $request, Opmerking $opmerking)
    {
        $opmerking->setGezien(!$opmerking->isGezien());
        $this->dao->update($opmerking);

        return new JsonResponse(['gezien' => $opmerking->isGezien()]);
    }

    /**
     * @Route("/add/{klant}")
     *
     * @ParamConverter("klant", class="AppBundle\Entity\Klant")
     */
    public function addAction(Request $request)
    {
        $klant = $request->get('klant');
        $opmerking = new Opmerking($klant);

        return $this->processForm($request, $opmerking);
    }

    /**
     * @Route("/{opmerking}/delete", methods={"POST"})
     *
     * @ParamConverter("opmerking", class="AppBundle\Entity\Opmerking")
     */
    public function deleteAction(Request $request, $opmerking)
    {
        $this->dao->delete($opmerking);

        return new JsonResponse();
    }
}
