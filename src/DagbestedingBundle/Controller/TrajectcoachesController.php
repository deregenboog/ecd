<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Trajectcoach;
use DagbestedingBundle\Form\TrajectcoachType;
use DagbestedingBundle\Service\TrajectcoachDaoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/trajectcoaches")
 */
class TrajectcoachesController extends AbstractController
{
    protected $title = 'Trajectcoach';
    protected $entityName = 'trajectcoach';
    protected $entityClass = Trajectcoach::class;
    protected $formClass = TrajectcoachType::class;
    protected $baseRouteName = 'dagbesteding_trajectcoaches_';

    /**
     * @var TrajectcoachDaoInterface
     */
    protected $dao;

    public function __construct(TrajectcoachDaoInterface $dao)
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
