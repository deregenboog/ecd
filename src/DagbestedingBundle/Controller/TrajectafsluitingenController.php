<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Trajectafsluiting;
use DagbestedingBundle\Form\TrajectafsluitingType;
use DagbestedingBundle\Service\TrajectafsluitingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/trajectafsluitingen")
 */
class TrajectafsluitingenController extends AbstractController
{
    protected $title = 'Afsluitingen trajecten';
    protected $entityName = 'Afsluiting traject';
    protected $entityClass = Trajectafsluiting::class;
    protected $formClass = TrajectafsluitingType::class;
    protected $baseRouteName = 'dagbesteding_trajectafsluitingen_';
    protected $templatePath = 'afsluitingen';

    /**
     * @var TrajectafsluitingDaoInterface
     *
     * @DI\Inject("dagbesteding.dao.trajectafsluiting")
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
