<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DagbestedingBundle\Entity\Trajectsoort;
use DagbestedingBundle\Form\TrajectsoortType;
use DagbestedingBundle\Service\TrajectsoortDaoInterface;

/**
 * @Route("/admin/trajectsoorten")
 */
class TrajectsoortenController extends AbstractController
{
    protected $title = 'Soorten trajecten';
    protected $entityName = 'Soort traject';
    protected $entityClass = Trajectsoort::class;
    protected $formClass = TrajectsoortType::class;
    protected $baseRouteName = 'dagbesteding_trajectsoorten_';

    /**
     * @var TrajectsoortDaoInterface
     *
     * @DI\Inject("dagbesteding.dao.trajectsoort")
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
