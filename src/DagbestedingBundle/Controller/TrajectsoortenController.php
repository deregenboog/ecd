<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Trajectsoort;
use DagbestedingBundle\Form\TrajectsoortType;
use DagbestedingBundle\Service\TrajectsoortDao;
use DagbestedingBundle\Service\TrajectsoortDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
     * @var TrajectsoortDao
     */
    protected $dao;

    /**
     * @param TrajectsoortDao $dao
     */
    public function __construct(TrajectsoortDao $dao)
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
