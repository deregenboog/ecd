<?php

namespace DagbestedingBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use DagbestedingBundle\Entity\Deelnemerafsluiting;
use DagbestedingBundle\Service\DeelnemerafsluitingDaoInterface;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Form\DeelnemerafsluitingType;

/**
 * @Route("/admin/deelnemerafsluitingen")
 */
class DeelnemerafsluitingenController extends AbstractController
{
    protected $title = 'Afsluitingen deelnemers';
    protected $entityName = 'Afsluiting deelnemer';
    protected $entityClass = Deelnemerafsluiting::class;
    protected $formClass = DeelnemerafsluitingType::class;
    protected $baseRouteName = 'dagbesteding_deelnemerafsluitingen_';
    protected $templatePath = 'afsluitingen';

    /**
     * @var DeelnemerafsluitingDaoInterface
     *
     * @DI\Inject("dagbesteding.dao.deelnemerafsluiting")
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
