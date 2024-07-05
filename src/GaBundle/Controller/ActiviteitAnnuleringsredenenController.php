<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use GaBundle\Entity\ActiviteitAnnuleringsreden;
use GaBundle\Form\ActiviteitAnnuleringsredenType;
use GaBundle\Service\ActiviteitAnnuleringsredenDaoInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/activiteitannuleringsredenen")
 *
 * @Template
 */
class ActiviteitAnnuleringsredenenController extends AbstractController
{
    protected $title = 'Annuleringsredenen activiteiten';
    protected $entityName = 'annuleringsreden activiteit';
    protected $entityClass = ActiviteitAnnuleringsreden::class;
    protected $formClass = ActiviteitAnnuleringsredenType::class;
    protected $baseRouteName = 'ga_activiteitannuleringsredenen_';

    /**
     * @var ActiviteitAnnuleringsredenDaoInterface
     */
    protected $dao;

    public function __construct(ActiviteitAnnuleringsredenDaoInterface $dao)
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
