<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Deelnemerafsluiting;
use DagbestedingBundle\Form\DeelnemerafsluitingType;
use DagbestedingBundle\Service\DeelnemerafsluitingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Routing\Annotation\Route;

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
