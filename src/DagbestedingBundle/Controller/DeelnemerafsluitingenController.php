<?php

namespace DagbestedingBundle\Controller;

use AppBundle\Controller\AbstractController;
use DagbestedingBundle\Entity\Deelnemerafsluiting;
use DagbestedingBundle\Form\DeelnemerafsluitingType;
use DagbestedingBundle\Service\DeelnemerafsluitingDaoInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/deelnemerafsluitingen")
 * @Template
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
    public function viewAction(Request $request, $id)
    {
        return $this->redirectToIndex();
    }
}
