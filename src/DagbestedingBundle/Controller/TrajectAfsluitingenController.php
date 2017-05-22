<?php

namespace DagbestedingBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use DagbestedingBundle\Service\TrajectAfsluitingDaoInterface;
use DagbestedingBundle\Entity\TrajectAfsluiting;
use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Form\TrajectAfsluitingType;

/**
 * @Route("/admin/trajectafsluitingen")
 */
class TrajectAfsluitingenController extends AbstractController
{
    protected $title = 'Afsluitingen trajecten';
    protected $entityName = 'Afsluiting traject';
    protected $entityClass = TrajectAfsluiting::class;
    protected $formClass = TrajectAfsluitingType::class;
    protected $baseRouteName = 'dagbesteding_trajectafsluitingen_';
    protected $templatePath = 'afsluitingen';

    /**
     * @var TrajectAfsluitingDaoInterface
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
