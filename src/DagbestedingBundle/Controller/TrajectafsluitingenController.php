<?php

namespace DagbestedingBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use DagbestedingBundle\Service\TrajectafsluitingDaoInterface;
use DagbestedingBundle\Entity\Trajectafsluiting;
use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Form\TrajectafsluitingType;

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
    public function viewAction($id)
    {
        return $this->redirectToIndex();
    }
}
