<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use GaBundle\Entity\DossierAfsluitreden;
use GaBundle\Form\DossierAfsluitredenType;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use GaBundle\Service\DossierAfsluitredenDaoInterface;

/**
 * @Route("/dossierafsluitredenen")
 * @Template
 */
class DossierAfsluitredenenController extends AbstractController
{
    protected $title = 'Afsluitredenen dossiers';
    protected $entityName = 'afsluitreden dossier';
    protected $entityClass = DossierAfsluitreden::class;
    protected $formClass = DossierAfsluitredenType::class;
    protected $baseRouteName = 'ga_dossierafsluitredenen_';

    /**
     * @var DossierAfsluitredenDaoInterface
     *
     * @DI\Inject("GaBundle\Service\DossierAfsluitredenDao")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
