<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use GaBundle\Entity\DossierAfsluitreden;
use GaBundle\Form\DossierAfsluitredenType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use GaBundle\Service\DossierAfsluitredenDao;

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
     * @var DossierAfsluitredenDao
     */
    protected $dao;

    /**
     * @param DossierAfsluitredenDao $dao
     */
    public function __construct(DossierAfsluitredenDao $dao)
    {
        $this->dao = $dao;
    }


    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
