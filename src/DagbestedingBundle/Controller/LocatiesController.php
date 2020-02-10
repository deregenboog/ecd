<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Locatie;
use DagbestedingBundle\Form\LocatieType;
use DagbestedingBundle\Service\LocatieDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/locaties")
 */
class LocatiesController extends AbstractController
{
    protected $title = 'Werklocaties';
    protected $entityName = 'Werklocatie';
    protected $entityClass = Locatie::class;
    protected $formClass = LocatieType::class;
    protected $baseRouteName = 'dagbesteding_locaties_';

    /**
     * @var LocatieDaoInterface
     *
     * @DI\Inject("DagbestedingBundle\Service\LocatieDao")
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
