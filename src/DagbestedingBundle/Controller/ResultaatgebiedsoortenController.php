<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Resultaatgebiedsoort;
use DagbestedingBundle\Form\ResultaatgebiedsoortType;
use DagbestedingBundle\Service\ResultaatgebiedsoortDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/resultaatgebiedsoorten")
 */
class ResultaatgebiedsoortenController extends AbstractController
{
    protected $title = 'Soorten resultaatgebieden';
    protected $entityName = 'Soort resultaatgebied';
    protected $entityClass = Resultaatgebiedsoort::class;
    protected $formClass = ResultaatgebiedsoortType::class;
    protected $baseRouteName = 'dagbesteding_resultaatgebiedsoorten_';

    /**
     * @var ResultaatgebiedsoortDaoInterface
     */
    protected $dao;

    public function __construct(ResultaatgebiedsoortDaoInterface $dao)
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
