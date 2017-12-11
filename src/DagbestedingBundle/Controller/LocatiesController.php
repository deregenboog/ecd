<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Locatie;
use DagbestedingBundle\Form\LocatieType;
use JMS\DiExtraBundle\Annotation as DI;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DagbestedingBundle\Service\LocatieDaoInterface;

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
     * @DI\Inject("dagbesteding.dao.locatie")
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
