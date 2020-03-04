<?php

namespace GaBundle\Controller;

use AppBundle\Controller\AbstractController;
use GaBundle\Entity\ActiviteitAnnuleringsreden;
use GaBundle\Form\ActiviteitAnnuleringsredenType;
use GaBundle\Service\ActiviteitAnnuleringsredenDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/activiteitannuleringsredenen")
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

    public function setContainer(\Psr\Container\ContainerInterface $container): ?\Psr\Container\ContainerInterface
    {
        $previous = parent::setContainer($container);

        $this->dao = $container->get("GaBundle\Service\ActiviteitAnnuleringsredenDao");
    
        return $previous;
    }

    /**
     * @Route("/{id}/view")
     */
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
