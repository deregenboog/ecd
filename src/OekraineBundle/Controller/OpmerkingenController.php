<?php

namespace OekraineBundle\Controller;

use AppBundle\Controller\AbstractController;
use AppBundle\Entity\Klant;
use AppBundle\Entity\Opmerking;
use OekraineBundle\Entity\Locatie;
use OekraineBundle\Form\OpmerkingType;
use OekraineBundle\Service\OpmerkingDao;
use OekraineBundle\Service\OpmerkingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/opmerkingen")
 * @Template
 */
class OpmerkingenController extends AbstractController
{
    protected $title = 'Opmerkingen';
    protected $entityName = 'opmerking';
    protected $entityClass = Opmerking::class;
    protected $formClass = OpmerkingType::class;
    protected $baseRouteName = 'oekraine_opmerkingen_';
    protected $disabledActions = ['view', 'edit'];

    /**
     * @var OpmerkingDao
     */
    protected $dao;

    /**
     * @param OpmerkingDao $dao
     */
    public function __construct(OpmerkingDao $dao)
    {
        $this->dao = $dao;
    }


    /**
     * @Route("/{klant}", requirements={"klant"="\d+"}, defaults={"locatie" = null})
     * @Route("/{klant}/{locatie}", requirements={"klant"="\d+", "locatie"="\d+"})
     * @ParamConverter("klant", class="AppBundle:Klant")
     * @ParamConverter("locatie", class="OekraineBundle:Locatie")
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
     * @ParamConverter("klant", class="AppBundle:Klant")
     */
    public function addAction(Request $request)
    {
        $klant = $request->get('klant');
        $opmerking = new Opmerking($klant);

        return $this->processForm($request, $opmerking);
    }

    /**
     * @Route("/{opmerking}/delete", methods={"POST"})
     * @ParamConverter("opmerking", class="AppBundle:Opmerking")
     */
    public function deleteAction(Request $request, $opmerking)
    {
        $this->dao->delete($opmerking);

        return new JsonResponse();
    }
}
