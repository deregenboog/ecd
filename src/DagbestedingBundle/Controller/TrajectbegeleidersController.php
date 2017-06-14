<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Trajectbegeleider;
use DagbestedingBundle\Form\TrajectbegeleiderType;
use DagbestedingBundle\Service\TrajectbegeleiderDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/trajectbegeleiders")
 */
class TrajectbegeleidersController extends AbstractController
{
    protected $title = 'Trajectbegeleiders';
    protected $entityName = 'Trajectbegeleider';
    protected $entityClass = Trajectbegeleider::class;
    protected $formClass = TrajectbegeleiderType::class;
    protected $baseRouteName = 'dagbesteding_trajectbegeleiders_';

    /**
     * @var TrajectbegeleiderDaoInterface
     *
     * @DI\Inject("dagbesteding.dao.trajectbegeleider")
     */
    protected $dao;

    /**
     * @Route("/{id}/view")
     */
    public function viewAction($id)
    {
        return $this->redirectToIndex();
    }
}
